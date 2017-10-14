<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use Auth;
use Mail;
use Carbon\Carbon;
use App;
use DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class ReportsController extends Controller {

	public $type = [
		'equipmentmasterlist' => 'Equipment Masterlist',
		'deployment' => 'Deployment',
		'preventivemaintenance' => 'Preventive Maintenance',
		'reservation' => 'Reservation',
		'transfer' => 'Transfer',
		'complaints' => 'Complaints',
		'roominventory' => 'Room Inventory',
		'profiling' => 'Profiling',
		'incident' => 'Incident',
		'workstationinventory' => 'Workstation Inventory per Room'
	];

	public $category = [
		'daily'=>'Daily',
		'weekly'=>'Weekly',
		'monthly'=>'Monthly',
		'annually'=>'Annually'
	];

	private $assessor_name;
	private $assessor_position;
	private $url;
	private $room;
	private $report;
	private $reportname;

	function __construct()
	{

		$this->assessor_name = App\User::where('accesslevel','=',0)
											->selectRaw('concat(firstname," ",middlename," ",lastname) as name')
								->first()
								->name;
		$this->assessor_position = "Laboratory Head"; 
		$this->room = App\Room::pluck('name','name');
	}

	public function index()
	{
		return view('report.index')
				->with('type',$this->type)
				->with('url',$this->url)
				->with('category',$this->category)
				->with('room',$this->room);
	}

	public function generate($report)
	{

		$this->report = $this->sanitizeString($report);
		$this->setReportName();
		$category = $this->sanitizeString(Input::get('category'));
		$date = $this->sanitizeString(Input::get("date"));
		$room = $this->sanitizeString(Input::get("room"));
		$view = view('errors.404');
		$start = '';
		$end = '';
		$title = '';

		switch($category)
		{
			case 'daily':
				$start = Carbon::parse($date)->startOfDay();
				$end = Carbon::parse($date)->endOfDay();
				$title = "Daily $this->reportname Report - ".$start->format('F d Y');
				break;
			case 'weekly':
				$start = Carbon::parse($date)->startOfWeek();
				$end = Carbon::parse($date)->endOfWeek();
				$title = "Weekly $this->reportname Report - ".$start->format('F d'). " - ".$end->format('d Y');
				break;
			case 'monthly':
				$start = Carbon::parse($date)->startOfMonth();
				$end = Carbon::parse($date)->endOfMonth();
				$title = "Monthly $this->reportname Report - ".$start->format('F d'). " - ".$end->format('d Y');
				break;
			case 'annually':
				$start = Carbon::parse($date)->startOfYear();
				$end = Carbon::parse($date)->endOfYear();
				$title = "Annual $this->reportname Report - ".$start->format('F d'). " - ".$end->format('F d Y');
				break;
		}

		if($this->report == 'equipmentmasterlist')
		{
			$inventory = App\Inventory::all();
			$view =  view('report.equipmentmasterlist')
						->with('inventory',$inventory);
		}

		if($this->report == 'deployment')
		{
			$deployment = $this->generateDeployment($start,$end);
			$view = view('report.deployment')
						->with('title',$title)
						->with('deployment',$deployment);

		}

		if($this->report == 'preventivemaintenance')
		{
			$ticket = $this->generatePreventiveMaintenance($start,$end);
			$view = view('report.preventivemaintenance')
						->with('title',$title)
						->with('ticket',$ticket);

		}

		if($this->report == 'reservation')
		{
			$reservation = $this->generateReservation($start,$end);
			$view = view('report.reservation')
						->with('title',$title)
						->with('reservation',$reservation);

		}

		if($this->report == 'transfer')
		{
			$transfer = $this->generateTransfer($start,$end);
			$view = view('report.transfer')
						->with('title',$title)
						->with('transfer',$transfer);

		}

		if($this->report == 'complaints')
		{
			$ticket = $this->generateComplaint($start,$end);
			$view = view('report.complaints')
						->with('title',$title)
						->with('ticket',$ticket);

		}

		if($this->report == 'incident')
		{
			$ticket = $this->generateIncident($start,$end);
			$view = view('report.incident')
						->with('title',$title)
						->with('ticket',$ticket);

		}

		if($this->report == 'profiling')
		{
			$profiling = $this->generateProfiling($start,$end);
			$view = view('report.profiling')
						->with('title',$title)
						->with('profiling',$profiling);

		}

		if($this->report == 'roominventory')
		{
			$roominventory =  $this->generateRoomInventory($room);

			$view = view('report.roominventory')
						->with('title',"Room Inventory - Laboratory Room $room")
						->with('roominventory',$roominventory);
		}

		if($this->report == 'workstationinventory')
		{
			$workstation = $this->generateWorkstationInventory($room);

			$view = view('report.workstationinventoryperroom')
					->with('title',"Workstation Inventory - Laboratory Room $room")
					->with('workstation',$workstation);
		}

		$view = $view->with('assessor_name',$this->assessor_name);
		$view = $view->with('assessor_position',$this->assessor_position);

		return $view;
	}

	public function generateRoomInventory($room)
	{
		return App\ItemProfile::where('location','=',$room)
				->join('item_v','itemprofile.propertynumber','=','item_v.propertynumber')
				->orderBy('itemtype')
				->get()
				->groupBy('itemtype')
				->transform(function($item,$key){
					 return $item->groupBy('brand')
					 			->sortBy('brand')
						 		->transform(function($item,$key){
								 	return $item->groupBy('model')
								 			->sortBy('model');
								 });
				});
	}

	public function generateWorkstationInventory($room)
	{
		return App\WorkstationView::where('location','=',$room)->get();
	}

	public function generateReservation($start,$end)
	{
		return App\ReservedItemsView::whereBetween('created_at',[ $start , $end ])->get();
	}

	public function generateTransfer($start,$end)
	{
		return App\ItemTransferView::whereBetween('timetransfered',[ $start , $end ])->get();
	}

	public function generateDeployment($start,$end)
	{
		return App\ItemProfile::whereBetween('deployment',[ $start , $end ])->get();
	}

	public function generateProfiling($start,$end)
	{
		return App\ItemProfile::join('item_v','itemprofile.propertynumber','=','item_v.propertynumber')
				->whereBetween('created_at',[ $start , $end ])
				->orderBy('itemtype')
				->get()
				->groupBy('itemtype')
				->transform(function($item,$key){
					 return $item->groupBy('brand')
					 			->sortBy('brand')
						 		->transform(function($item,$key){
								 	return $item->groupBy('model')
								 			->sortBy('model')
									 		->transform(function($item,$key){
											 	return $item->groupBy('profiled_by')
											 			->sortBy('profiled_by');
											 });
								 });
				});
	}

	public function generatePreventiveMaintenance($start,$end)
	{	
		return App\TicketView::tickettype('maintenance')->whereBetween('date',[ $start , $end ])->get();
	}

	public function generateComplaint($start,$end)
	{	
		return App\TicketView::tickettype('complaint')->whereBetween('date',[ $start , $end ])->get();
	}

	public function generateIncident($start,$end)
	{	
		return App\TicketView::tickettype('incident')->whereBetween('date',[ $start , $end ])->get();
	}

	public function setReportName()
	{
		if($this->report == 'equipmentmasterlist')
		{
			$this->reportname = "Equipment Masterlist";
		}

		if($this->report == 'deployment')
		{
			$this->reportname = "Deployment";

		}

		if($this->report == 'preventivemaintenance')
		{
			$this->reportname = "Preventive Maintenance";

		}

		if($this->report == 'reservation')
		{
			$this->reportname = "Reservation";

		}

		if($this->report == 'transfer')
		{
			$this->reportname = "Transfer";

		}

		if($this->report == 'complaints')
		{
			$this->reportname = "Complaints";

		}

		if($this->report == 'roominventory')
		{
			$this->reportname = "Room Inventory";
		}

		if($this->report == 'profiling')
		{
			$this->reportname = "Profiling";
		}

		if($this->report == 'incident')
		{
			$this->reportname = "Incident";
		}

		if($this->report == 'workstationinventory')
		{
			$this->reportname = "Workstation Inventory";
		}
	}

}
