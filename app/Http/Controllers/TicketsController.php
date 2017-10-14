<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon;
use Session;
use Validator;
use Auth;
use App;
use App\TicketView;
use App\Ticket;
use App\PcTicket;
use App\RoomTicket;
use App\Room;
use App\ItemProfile;
use App\MaintenanceActivity;
use App\Pc;
use DB;
use App\User;
use App\TicketType;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class TicketsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{

			$staff_id = null;
			$type = "";
			$assigned = "";
			$status = "";

			/*
			|--------------------------------------------------------------------------
			|
			| 	Laboratory Staff 
			|
			|--------------------------------------------------------------------------
			|
			*/

			$query = TicketView::orderBy('date','desc');
			if( Auth::user()->accesslevel == 2 )
			{
				$staff_id = Auth::user()->id;
				$query = $query->staff($staff_id);
			}

			if(Input::has('type'))
			{
				$type = $this->sanitizeString(Input::get('type'));
				$query = $query->tickettype($type);
			}

			if(Input::has('assigned'))
			{
				$assigned = $this->sanitizeString(Input::get('assigned'));
			}

			if(Input::has('status'))
			{
				$status = $this->sanitizeString(Input::get('status'));
				$query = $query->status($status);
			}

			if( Auth::user()->accesslevel == 3 || Auth::user()->accesslevel == 4  )
			{
				$query = $query->self()->tickettype('Complaint');
			}

			return json_encode([ 
				'data' => $query->get()
		 	]);
		}

		$ticket = Ticket::orderBy('created_at', 'desc')->first();

		if (count($ticket) == 0 ) 
		{
			$ticket = 1;
		} 
		else if ( count($ticket) > 0 ) 
		{
			$ticket = $ticket->id + 1;
		}

		$total_tickets = App\Ticket::count();
		$complaints = App\Ticket::tickettype('complaint')
						->open()
						->count();

		$authored_tickets = App\Ticket::where('author','=',Auth::user()->firstname." ".Auth::user()->middlename." ".Auth::user()->lastname)
						->count();
		$open_tickets = App\Ticket::tickettype('complaint')
						->open()
						->count();
		
		return view('ticket.index')
				->with('tickettype',TicketType::all())
				->with('ticketstatus',['Open','Closed'])
				->with('lastticket',$ticket)
				->with('total_tickets',$total_tickets)
				->with('complaints',$complaints)
				->with('authored_tickets',$authored_tickets)
				->with('open_tickets',$open_tickets);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$ticket = Ticket::orderBy('created_at', 'desc')->first();

		if (count($ticket) == 0 ) 
		{
			$ticket = 1;
		} 
		else if ( count($ticket) > 0 ) 
		{
			$ticket = $ticket->id + 1;
		}

		$staff = User::staff()
						->whereNotIn('id',[ Auth::user()->id, User::admin()->first()->id ])
						->select(
							'id as id',
							DB::raw('CONCAT( firstname , " " , middlename , " " , lastname ) as name')
						)
						->pluck('name','id');

		return view('ticket.create')
				->with('lastticket',$ticket)
				->with('staff',$staff);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$tag = $this->sanitizeString(Input::get('tag'));
		$ticketname = "";	
		$tickettype = 'complaint';

		if(Input::has('tickettype'))
		{
			$tickettype = 'incident';
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Verifies if the user inputs  a title
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('title'))
		{
			$ticketname = $this->sanitizeString(Input::get('title'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if ticketname has no value
			|	if no value, type will be automatically complaint
			|
			|--------------------------------------------------------------------------
			|
			*/
			if($ticketname == '' || $ticketname == null)
			{
				$ticketname = $tickettype;
			} 
		}
		else
		{
			$ticketname = $tickettype;
		}
	
		/*
		|--------------------------------------------------------------------------
		|
		| 	Verifies if the user inputs an author
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('author'))
		{
			$author = $this->sanitizeString(Input::get('author'));
		}	
		else
		{
			$author = Auth::user()->firstname . " " . Auth::user()->middlename . " " .Auth::user()->lastname;
		}

		$details = $this->sanitizeString(Input::get('description'));
	
		/*
		|--------------------------------------------------------------------------
		|
		| 	Verifies if the user inputs an author
		|
		|--------------------------------------------------------------------------
		|
		*/
		$user = User::where('accesslevel','=',0)->first();
		$staffassigned = null;

		if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)
		{
			if(Input::has('staffassigned'))
			{
				$staffassigned = $this->sanitizeString(Input::get('staffassigned'));
			}
			else
			{
				$staffassigned = Auth::user()->id;
			}
		}

		$status = 'Open';
		$ticket_id = null;

		$validator = Validator::make([
				'Ticket Title' => $ticketname,
				'Details' => $details,
				'Author' => $author,
			],Ticket::$complaintRules);

		if($validator->fails())
		{
			return redirect('ticket/create')
				->withInput()
				->withErrors($validator);
		}
	

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is equipment
		|
		|--------------------------------------------------------------------------
		|
		*/
		$itemprofile = ItemProfile::propertyNumber($tag)->first();
		if( count($itemprofile) > 0)
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the equipment is connected to pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			$pc = Pc::isPc($tag);
			if(count($pc) > 0)
			{
				Ticket::generatePcTicket($pc->id,$tickettype,$ticketname,$details,$author,$staffassigned,$ticket_id,$status);
			} 
			else
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	Create equipment ticket
				|
				|--------------------------------------------------------------------------
				|
				*/
				Ticket::generateEquipmentTicket($itemprofile->id,$tickettype,$ticketname,$details,$author,$staffassigned,$ticket_id,$status);
			}

		} 
		else 
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the tag is room
			|
			|--------------------------------------------------------------------------
			|
			*/
			$room = Room::location($tag)->first();
			if( count($room) > 0 ) 
			{
				Ticket::generateRoomTicket($room->id,$tickettype,$ticketname,$details,$author,$staffassigned,$ticket_id,$status);
			}
			else
			{
				/*
				|--------------------------------------------------------------------------
				|
				| 	Check if the equipment is connected to pc
				|
				|--------------------------------------------------------------------------
				|
				*/
				$pc = Pc::isPc($tag);
				if(count($pc) > 0)
				{
					Ticket::generatePcTicket($pc->id,$tickettype,$ticketname,$details,$author,$staffassigned,$ticket_id,$status);
				} 
				else
				{
					
					/*
					|--------------------------------------------------------------------------
					|
					| 	Create general ticket
					|
					|--------------------------------------------------------------------------
					|
					*/
					Ticket::generateTicket($tickettype,$ticketname,$details,$author,$staffassigned,$ticket_id,$status);
				}

			}
		}

		Session::flash('success-message','Ticket Generated');
		return redirect('ticket');

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$ticket = Ticket::find($id);
		return view('ticket.edit')
				->with('ticket',$ticket);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$propertynumber = $this->sanitizeString(Input::get('propertynumber'));
		$type = $this->sanitizeString(Input::get('type'));
		$maintenancetype = $this->sanitizeString(Input::get('maintenancetype'));
		$category = $this->sanitizeString(Input::get('category'));
		$author = $this->sanitizeString(Input::get('author'));
		$details = $this->sanitizeString(Input::get('description'));
		$staffassigned = Auth::user()->id;
		$propertynumber;

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check maintenance type
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($type == 'maintenance')
		{
			$maintenancetype = 'maintenance type:'. $maintenancetype . ',details:';
		}
		else
		{
			 $maintenancetype = "";
		}

		try
		{
			$item = ItemProfile::where('propertynumber','=',trim($propertynumber))->first();
			if(count($item) == 0)
			{
				return redirect("ticket/$id/edit")
						->withInput()
						->withErrors([ 'Invalid Property Number' ]);
			}

			$propertynumber = $item->id;
		} 
		catch ( Exception $e ) 
		{
			return redirect("ticket/$id/edit")
					->withInput()
					->withErrors([ 'Invalid Property Number' ]);
		}

		$validator = Validator::make([
				'Item Id' => $propertynumber,
				'Ticket Type' => $category,
				'Ticket Name' => $type,
				'Details' => $details,
				'Author' => $author,
		],Ticket::$rules);

		if($validator->fails())
		{
			return redirect("ticket/$id/edit")
				->withInput()
				->withErrors($validator);
		}

		try{

			$ticket = Ticket::find($id);
			$ticket->item_id = $propertynumber;
			$ticket->ticketname = $category;
			$ticket->tickettype = $type;
			$ticket->details = $maintenancetype . $details;
			$ticket->author = $author;
			$ticket->save();
		} 
		catch (Exception $e) 
		{
			Session::flash('error-message','Error occured while processiong your data');
			return redirect("ticket/$id/edit")
				->withInput();
		}

		Session::flash('success-message','Ticket Generated');
		return redirect('ticket');

	}

	/**
	 * Transfer ticket to another user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function transfer($id = null)
	{
		/*
		|--------------------------------------------------------------------------
		|
		| 	Initialize
		|
		|--------------------------------------------------------------------------
		|
		*/
		$id = $this->sanitizeString(Input::get('id'));
		$staffassigned = $this->sanitizeString(Input::get('transferto'));
		$comment = $this->sanitizeString(Input::get('comment'));

		/*
		|--------------------------------------------------------------------------
		|
		| 	Validation
		|
		|--------------------------------------------------------------------------
		|
		*/
		$validator = Validator::make([
				'Ticket ID' => $id,
				'Staff Assigned' => $staffassigned
			],Ticket::$transferRules);

		if($validator->fails())
		{
			Session::flash('error-message','Problem encountered while processing your request');
			return redirect('ticket');
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Transfer....
		|
		|--------------------------------------------------------------------------
		|
		*/
		try
		{
			Ticket::transferTicket($id,$staffassigned,$comment);
		} 
		catch ( Exception $e ) 
		{
			Session::flash('error-message','Problem encountered while processing your request');
			return redirect('ticket');

		}

		Session::flash('success-message','Ticket Transferred');
		return redirect('ticket');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Request::ajax())
		{
			try
			{
				Ticket::closeTicket($id);
				return json_encode('success');
			} 
			catch ( Exception $e ) 
			{
				return json_encode('error');
			}
		}
	}

	/**
	 * Restore the specified resource
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function reOpenTicket($id)
	{
		if(Request::ajax())
		{
			try
			{
				Ticket::reOpenTicket($id);
				return json_encode('success');
			} 
			catch ( Exception $e ) 
			{
				return json_encode('error');
			}
		}
	}

	public function showHistory($id)
	{		
		if(Request::ajax())
		{
			$arraylist = array();
			$cond = true;
			$start = 0;
			$ticket;	
			do
			{	

				/*
				|--------------------------------------------------------------------------
				|
				| 	$start = 0	->	original
				|	$start = 1	->	next ticket
				|	$start = 2	->	last
				|
				|--------------------------------------------------------------------------
				|
				*/
				if($start == 0)
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	Get all the previous ticket
					|
					|--------------------------------------------------------------------------
					|
					*/
					$ticket =  Ticket::where('ticket_id','=',$id)
								->orderBy('id','desc')
								->with('user')
								->whereNotIn('id',array_pluck($arraylist,'id'))
								->first();
				}
				else
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	Get all the ticket connected to the original
 					|
					|--------------------------------------------------------------------------
					|
					*/
					$ticket =  Ticket::where('id','=',$id)
								->orderBy('id','desc')
								->whereNotIn('id',array_pluck($arraylist,'id'))
								->with('user')
								->first();
				}

				try 
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	Ticket exists
					|
					|--------------------------------------------------------------------------
					|
					*/
					if(isset($ticket))
					{		

						/*
						|--------------------------------------------------------------------------
						|
						| 	If original
						|
						|--------------------------------------------------------------------------
						|
						*/
						if($start == 1)
						{
							$id = $ticket->ticket_id;
						}	

						array_push($arraylist,$ticket);
					}
					else
					{
						if($start == 2)
						{

							/*
							|--------------------------------------------------------------------------
							|
							| 	all connected ticket are used
							|
							|--------------------------------------------------------------------------
							|
							*/
							$cond  = false;
						}
						else if($start == 1)
						{

							/*
							|--------------------------------------------------------------------------
							|
							| 	no more previous ticket
							|
							|--------------------------------------------------------------------------
							|
							*/
							$start = 2;
						} 
						else
						{
							$start = 1;
						}
					}
				} 
				catch( Exception $e ) 
				{ 
					$cond = false;
				}


			} while ( $cond == true);

			return json_encode([ 'data'=> $arraylist ]);
		}

		try
		{
		
			$ticket = TicketView::where('id','=',$id)
								->first();

			$lastticket = Ticket::orderBy('created_at', 'desc')->first();

			if (count($lastticket) == 0 ) 
			{
				$lastticket = 1;
			} 
			else if ( count($lastticket) > 0 ) 
			{
				$lastticket = $lastticket->id + 1;
			}

			if(!isset($ticket) || count($ticket) <= 0)
			{
				return redirect('ticket');
			}

			return view('ticket.history')
				->with('ticket',$ticket)
				->with('id',$id)
				->with('lastticket',$lastticket);
		} 
		catch ( Exception $e ) 
		{

			Session::flash('error-message','Problem encountered while processing your request');
			return redirect('ticket');

		}
	}

	/**
	*
	*	@return ajax: 'success' or 'error'
	*	normal: view with prompt
	*
	*
	*/
	public function resolve()
	{
		/*
		|--------------------------------------------------------------------------
		|
		| 	Intantiate Values
		|
		|--------------------------------------------------------------------------
		|
		*/
		$details = "";
		$id = $this->sanitizeString(Input::get('id'));
		$status = 'Open';
		$underrepair = false;

		if(Input::has('contains'))
		{
			$details = $this->sanitizeString(Input::get('details'));
		} else 
		{	

			/*
			|--------------------------------------------------------------------------
			|
			| 	use maintenance activity
			|
			|--------------------------------------------------------------------------
			|
			*/
			try 
			{
				/*
				|--------------------------------------------------------------------------
				|
				| 	get the activity field
				|
				|--------------------------------------------------------------------------
				|
				*/
				$activity = $this->sanitizeString(Input::get('activity'));
				$maintenanceactivity = MaintenanceActivity::find($activity);
				$details = $maintenanceactivity->activity;
			} catch (Exception $e) {}
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if the status will be changed
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('underrepair'))
		{
			$underrepair = 'underrepair';
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if the status will be changed
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('working'))
		{
			$underrepair = 'working';
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if the the ticket will be closed
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('close'))
		{
			$status = "Closed";
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	validates
		|
		|--------------------------------------------------------------------------
		|
		*/
		$validator = Validator::make([
				'Details' => $details
		],Ticket::$maintenanceRules);

		if($validator->fails())
		{

			return redirect('ticket')
				->withInput()
				->withErrors($validator);
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	call function resolve ticket
		|
		|--------------------------------------------------------------------------
		|
		*/
		Ticket::resolveTicket($id,$details,$status,$underrepair);

		/*
		|--------------------------------------------------------------------------
		|
		| 	return successful
		|
		|--------------------------------------------------------------------------
		|
		*/
		Session::flash('success-message','Action Created');
		return redirect('ticket');
	}

	/**
	*
	*	complain process
	*
	*/
	public function complaint()
	{
		return redirect('ticket/complaint');
	}

	/**
	*
	*	@return complaint view
	*	@return opened ticket
	*
	*/
	public function complaintViewForStudentAndFaculty()
	{
		if(Request::ajax())
		{
			return json_encode([
					'data' => Ticket::with('itemprofile')
										->with('user')
										->where('status','=','Open')
										->get()
				]);
		}
		return view('ticket.complaint');
	}

	/**
	*
	*	@param $id requires pc id
	*	@return list of pc ticket
	*
	*/
	public function getPcTicket($id)
	{
		if(Request::ajax())
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	get pc id
			|
			|--------------------------------------------------------------------------
			|
			*/
			$pc = PcTicket::where('pc_id','=',$id)->pluck('id');

			/*
			|--------------------------------------------------------------------------
			|
			| 	return ticket with pc information
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
			[
				'data' => Ticket::whereIn('id',function($query) use ($id)
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	checks if pc is in ticket
					|
					|--------------------------------------------------------------------------
					|
					*/
					$query->where('pc_id','=',$id)
						->from('pc_ticket')
						->select('ticket_id')
						->pluck('ticket_id');
				})->get()
			]);
		}
		
		/*
		|--------------------------------------------------------------------------
		|
		| 	get pc id
		|
		|--------------------------------------------------------------------------
		|
		*/
		$pc = PcTicket::where('pc_id','=',$id)->pluck('id');

		/*
		|--------------------------------------------------------------------------
		|
		| 	return ticket with pc information
		|
		|--------------------------------------------------------------------------
		|
		*/
		return json_encode(
		[
			'data' => Ticket::whereIn('id',function($query) use ($id)
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	checks if pc is in ticket
				|
				|--------------------------------------------------------------------------
				|
				*/
				$query->where('pc_id','=',$id)
					->from('pc_ticket')
					->select('ticket_id')
					->pluck('ticket_id');
			})->get()
		]);
	}

	/**
	*
	*	@param $id requires pc id
	*	@return list of room ticket
	*
	*/
	public function getRoomTicket($id)
	{
		if(Request::ajax())
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	get room id
			|
			|--------------------------------------------------------------------------
			|
			*/
			$pc = RoomTicket::where('room_id','=',$id)->pluck('id');

			/*
			|--------------------------------------------------------------------------
			|
			| 	return ticket with room information
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
			[
				'data' => Ticket::whereIn('id',function($query) use ($id)
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	checks if room is in ticket
					|
					|--------------------------------------------------------------------------
					|
					*/
					$query->where('room_id','=',$id)
						->from('room_ticket')
						->select('ticket_id')
						->pluck('ticket_id');
				})->get()
			]);
		}
	}

	/**
	*
	*	@param $tag 
	*	@return item information 
	*	@return is existing room
	*	@return pc information
	*
	*/
	public function getTagInformation()
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	uses ajax request
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			$tag = $this->sanitizeString(Input::get('id'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the equipment is connected to pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			$pc = Pc::isPc($tag);
			if(count($pc) > 0)
			{
				$pc = Pc::with('systemunit')->with('monitor')->with('keyboard')->with('avr')->find($pc->id);
				return json_encode($pc);
			} 
			
			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the tag is equipment
			|
			|--------------------------------------------------------------------------
			|
			*/
			$itemprofile = ItemProfile::propertyNumber($tag)->first();
			if( count($itemprofile) > 0)
			{
				/*
				|--------------------------------------------------------------------------
				|
				| 	Create equipment ticket
				|
				|--------------------------------------------------------------------------
				|
				*/
				return json_encode($itemprofile);

			} 

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the tag is room
			|
			|--------------------------------------------------------------------------
			|
			*/
			$room = Room::location($tag)->first();
			if( count($room) > 0 ) 
			{
				return json_encode($room);
			}

			/*
			|--------------------------------------------------------------------------
			|
			| 	return false if no item found
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode('error');
		}

		$tag = $this->sanitizeString(Input::get('tag'));

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is equipment
		|
		|--------------------------------------------------------------------------
		|
		*/
		$itemprofile = ItemProfile::propertyNumber($tag)->first();
		if( count($itemprofile) > 0)
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the equipment is connected to pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			$pc = Pc::isPc($tag);
			if(count($pc) > 0)
			{
				return $pc;
			} 
			else
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	Create equipment ticket
				|
				|--------------------------------------------------------------------------
				|
				*/
				return $itemprofile;
			}

		} 
		else 
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if the tag is room
			|
			|--------------------------------------------------------------------------
			|
			*/
			$room = Room::location($tag)->first();
			if( count($room) > 0 ) 
			{
				return $room;
			}
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	return false if no item found
		|
		|--------------------------------------------------------------------------
		|
		*/
		return json_encode('error');
	}

	/**
	*
	*	maintenance view 
	*
	*/
	public function maintenanceView()
	{
		$ticket = Ticket::orderBy('created_at', 'desc')->first();
		$activity = MaintenanceActivity::pluck('activity','id');

		if (count($ticket) == 0 ) 
		{
			$ticket = 1;
		} 
		else if ( count($ticket) > 0 ) 
		{
			$ticket = $ticket->id + 1;
		}

		if(count($activity) == 0)
		{
			$activity = [ 'None' => 'No suggestion available' ];
		}

		return view('ticket.maintenance')
				->with('lastticket',$ticket)
				->with('activity',$activity);
	}


	/**
	 * Maintenance function.
	 *
	 * @return Response
	 */
	public function maintenance()
	{
		/*
		|--------------------------------------------------------------------------
		|
		| 	init ...
		|
		|--------------------------------------------------------------------------
		|
		*/
		$tag = $this->sanitizeString(Input::get('tag'));
		$ticketname = "Maintenance Ticket";	
		$underrepair = false;	
		$workstation = false;

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if item is not in the field list
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('contains'))
		{
			$details = $this->sanitizeString(Input::get('description'));
		} 
		else 
		{	

			/*
			|--------------------------------------------------------------------------
			|
			| 	use maintenance activity
			|
			|--------------------------------------------------------------------------
			|
			*/
			try 
			{
				/*
				|--------------------------------------------------------------------------
				|
				| 	get the activity field
				|
				|--------------------------------------------------------------------------
				|
				*/
				$activity = $this->sanitizeString(Input::get('activity'));
				$maintenanceactivity = MaintenanceActivity::find($activity);
				$ticketname = $maintenanceactivity->activity;
				$details = $maintenanceactivity->details;
			} catch (Exception $e) {}
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if item will be set to underrepair
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('underrepair'))
		{
			$underrepair = true;
		} 

		/*
		|--------------------------------------------------------------------------
		|
		| 	validates
		|
		|--------------------------------------------------------------------------
		|
		*/
		$validator = Validator::make([
				'Details' => $details
		],Ticket::$maintenanceRules);

		if($validator->fails())
		{
			return redirect('ticket/create')
				->withInput()
				->withErrors($validator);
		}

		DB::beginTransaction();

		$tickettype = 'Maintenance';
		$author = Auth::user()->firstname . ' ' . Auth::user()->middlename . ' ' . Auth::user()->lastname;
		$staffassigned = Auth::user()->id;
		$status = 'Closed';
		$item = Input::get('item');

		Ticket::generateMaintenanceTicket($tag,$ticketname,$details,$underrepair,$workstation);

		if(count($item) > 0)
		{

			foreach($item as $item)
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	Check if the tag is equipment
				|
				|--------------------------------------------------------------------------
				|
				*/
				$itemprofile = ItemProfile::propertyNumber($item)->first();
				if( count($itemprofile) > 0)
				{

					/*
					|--------------------------------------------------------------------------
					|
					| 	Create equipment ticket
					|
					|--------------------------------------------------------------------------
					|
					*/
					Ticket::generateEquipmentTicket($itemprofile->id,$tickettype,$ticketname,$details,$author,$staffassigned,null,$status);

				}
				else
				{
					/*
					|--------------------------------------------------------------------------
					|
					| 	Check if the equipment is connected to pc
					|
					|--------------------------------------------------------------------------
					|
					*/
					$pc = Pc::isPc($item);
					if(count($pc) > 0)
					{
						Ticket::generatePcTicket($pc->id,$tickettype,$ticketname,$details,$author,$staffassigned,null,$status);
					} 
				} 
			}

		}

		DB::commit();

		Session::flash('success-message','Ticket Generated');
		return redirect('ticket');

	}

}
