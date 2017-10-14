<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use Auth;
use App\Pc;
use App\Software;
use App\Supply;
use App\Ticket;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class WorkstationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax())
		{
			return json_encode(['data'=> Pc::with('keyboard','avr','monitor','systemunit.roominventory.room')->get()]);
		}

		return view('workstation.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('workstation.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$systemunit = $this->sanitizeString(Input::get('systemunit'));
		$monitor = $this->sanitizeString(Input::get('monitor'));
		$avr = $this->sanitizeString(Input::get('avr'));
		$keyboard = $this->sanitizeString(Input::get('keyboard'));
		$oskey = $this->sanitizeString(Input::get('os'));
		$mouse = $this->sanitizeString(Input::get('mouse'));
		$name = $this->sanitizeString(Input::get('name'));

		$validator = Validator::make([
			'Operating System Key' => $oskey,
			'Workstation Name' => $name,
			'avr' => $avr,
			'Keyboard' => $keyboard,
			'Monitor' => $monitor,
			'System Unit' => $systemunit,
			'Mouse' => $mouse
		],Pc::$rules);

		if($validator->fails())
		{
			return redirect('workstation/create')
					->withInput()
					->withErrors($validator);
		}

		Pc::assemble($name,$systemunit,$monitor,$avr,$keyboard,$oskey,$mouse);
		Session::flash('success-message','Workstation assembled');
		return redirect('workstation');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

		if(Request::ajax())
		{
			$workstation = Pc::find($id);

			return json_encode([
				'data' => Software::whereHas('roomsoftware',function($query) use ($workstation) {
								$query->where('room_id','=',$workstation->systemunit->roominventory->room_id);
							})
							->with('pcsoftware.softwarelicense')
							->get()
			]);
		}

		try{

			$room = "";
			$software = "";
			$workstation = Pc::with('systemunit')
						->with('keyboard')
						->with('monitor')
						->find($id);

			if($workstation)
			{
				$room = $workstation->systemunit->roominventory->room_id;

				try
				{
					$software = Software::whereHas('roomsoftware',function($query) use ($room) {
								$query->where('room_id','=',$room);
							})->get();
				} 
				catch (Exception $e) 
				{ 
					$software = '';
				}
			}

			$total = 0;
			$mouseissued = 0;

			$mouseissued = Ticket::whereIn('id',function($query) use ($id)
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
			})->where('details','like','%'.'As Mouse Brand' . '%')->count();

			$total = Ticket::whereIn('id',function($query) use ($id)
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
			})->where('tickettype','=','Complaint')->count();

			return view('workstation.show')
				->with('workstation',$workstation)
				->with('software',$software)
				->with('total_tickets',$total)
				->with('mouseissued',$mouseissued);
		} 

		catch (Exception $e) 
		{
			return redirect('workstation');
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$pc = Pc::where('id','=',$id)
					->with('keyboard','avr','monitor','systemunit.roominventory.room')
					->first();

		return view('workstation.edit')
			->with('pc',$pc);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$avr = $this->sanitizeString(Input::get('avr'));
		$monitor = $this->sanitizeString(Input::get('monitor'));
		$os = $this->sanitizeString(Input::get('os'));
		$keyboard = $this->sanitizeString(Input::get('keyboard'));
		$mouse = $this->sanitizeString(Input::get('mouse'));

		$validator = Validator::make([
		  'Operating System Key' => $os
		],Pc::$updateRules);

		if($validator->fails())
		{
		  return redirect("workstation/$id/edit")
		    ->withInput()
		    ->withErrors($validator);
		}

		$details = "Workstation updated with the following propertynumber:" ;

		$pc = Pc::find($id);
		$pc->oskey = $os;

		if(Input::has('mousetag'))
		{

			$validator = Validator::make([ 'mouse'=>$mouse ],[
			  'mouse' => 'required|exists:supply,brand'
			]);

			if($validator->fails())
			{
			  return redirect("workstation/$id/edit")
			    ->withInput()
			    ->withErrors($validator);
			}

			Supply::releaseForWorkstation($mouse);
			$pc->mouse = $mouse;
			$details = $details .  "$mouse as mouse brand";
		}

		if(Input::has('monitor'))
		{

			$validator = Validator::make([ 'monitor' => $monitor ],[
			  'monitor' => 'required|exists:itemprofile,propertynumber'
			]);

			if($validator->fails())
			{
			  return redirect("workstation/$id/edit")
			    ->withInput()
			    ->withErrors($validator);
			}

			$pc->monitor = $monitor;
			$details = $details . "$_monitor->propertynumber for Monitor ";
		}

		if(Input::has('avr'))
		{

			$validator = Validator::make([ 'avr' => $avr ],[
			  'avr' => 'required|exists:itemprofile,propertynumber'
			]);

			if($validator->fails())
			{
			  return redirect("workstation/$id/edit")
			    ->withInput()
			    ->withErrors($validator);
			}

			$pc->$avr = $avr;
			$details = $details . "$_avr->propertynumber for AVR";
		}

		if(Input::has('keyboard'))
		{

			$validator = Validator::make([ 'keyboard' => $keyboard ],[
			  'keyboard' => 'required|exists:itemprofile,propertynumber'
			]);

			if($validator->fails())
			{
			  return redirect("workstation/$id/edit")
			    ->withInput()
			    ->withErrors($validator);
			}

			$pc->$keyboard = $keyboard;
			$details = $details . "$_keyboard->propertynumber for Keyboard";
		}

		$pc->save();

		$ticketname = 'Workstation Update';
		$staffassigned = Auth::user()->id;
		$author = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
		Ticket::generatePcTicket($pc->id,'Receive',$ticketname,$details,$author,$staffassigned,null,'Closed');

		Session::flash('success-message','Workstation  updated');
		return redirect('workstation');
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
			$pc = $this->sanitizeString(Input::get('selected'));
			$keyboard = $this->sanitizeString(Input::get('keyboard'));
			$avr = $this->sanitizeString(Input::get('avr'));
			$monitor = $this->sanitizeString(Input::get('monitor'));
			$systemunit = $this->sanitizeString(Input::get('systemunit'));
			try
			{
				Pc::condemn($pc,$systemunit,$monitor,$keyboard,$avr);
			} 
			catch ( Exception $e ) 
			{  
				return json_encode('error');
			}

			return json_encode('success');
		}

		$pc = $this->sanitizeString(Input::get('selected'));
		Pc::condemn($pc,$systemunit,$monitor,$keyboard,$avr);

		Session::flash('success-message','Workstation condemned');
		return redirect('workstation');
	}

	/**
	*
	*	function for deploying pc to another location
	*	@param $room accepts room name
	*	@param $pc accepts pc id list
	*
	*/
	public function deploy()
	{

		/**
		*
		*	check if the request is ajax
		*
		*/
		if(Request::ajax())
		{
			$room = $this->sanitizeString(Input::get('room'));
			$pc = $this->sanitizeString(Input::get('items'));
			$name = $this->sanitizeString(Input::get('name'));

			Pc::setPcLocation($pc,$room);
			$pc = Pc::find($pc);
			$pc->name = $name;
			$pc->save();

			return json_encode('success');
		}

		/**
		*
		*	normal request
		*
		*/
		$room = $this->sanitizeString(Input::get('room'));
		$pc = $this->sanitizeString(Input::get('items'));
		$name = $this->sanitizeString(Input::get('name'));

		Pc::setPcLocation($pc,$room);
		$pc = Pc::find($pc);
		$pc->name = $name;
		$pc->save();

		Session::flash('success-message','Workstation deployed');
		return redirect('workstation/form/deployment');
	}

	/**
	*
	*	function for transfering pc to another location
	*	@param $room accepts room name
	*	@param $pc accepts pc id list
	*
	*/
	public function transfer()
	{

		/**
		*
		*	check if the request is ajax
		*
		*/
		if(Request::ajax())
		{
			$room = $this->sanitizeString(Input::get('room'));
			$pc = $this->sanitizeString(Input::get('items'));
			$name = $this->sanitizeString(Input::get('name'));

			Pc::setPcLocation($pc,$room);
			$pc = Pc::find($pc);
			$pc->name = $name;
			$pc->save();

			return json_encode('success');
		}

		/**
		*
		*	normal request
		*
		*/
		$room = $this->sanitizeString(Input::get('room'));
		$pc = $this->sanitizeString(Input::get('items'));
		$name = $this->sanitizeString(Input::get('name'));

		Pc::setPcLocation($pc,$room);
		$pc = Pc::find($pc);
		$pc->name = $name;
		$pc->save();

		Session::flash('success-message','Workstation transferred');
		return redirect('workstation/view/transfer');
	}

}
