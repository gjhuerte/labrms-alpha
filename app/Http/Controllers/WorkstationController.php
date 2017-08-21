<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\Pc;
use App\Software;
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

		$validator = Validator::make([
			'Operating System Key' => $oskey,
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

		Pc::assemble($systemunit,$monitor,$avr,$keyboard,$oskey,$mouse);
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
				try
				{
					$room = $workstation->systemunit->roominventory->room_id;
				}
				catch ( Exception $e ) 
				{
					try 
					{
						$room = $workstation->monitor->roominventory->room_id;
					} 
					catch ( Exception $e ) 
					{
						try
						{
							$room = $workstation->keyboard->roominventory->room_id;
						}
						catch (Exception $e ) 
						{

							$room = $workstation->avr->roominventory->room_id;
						}
					}
				}


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

			return view('workstation.show')
				->with('workstation',$workstation)
				->with('software',$software);

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
		$systemunit = $this->sanitizeString(Input::get('systemunit'));
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

		$pc = Pc::find($id);
		$pc->oskey = $os;
		$pc->mouse = $mouse;

		$pc->save();

		Session::flash('success-message','Workstation information updated');
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
			foreach( Pc::separateArray($pc) as $pc )
			{
				try{
					$pc = Pc::find($pc);
					$pc->delete();
				} catch ( Exception $e ) {  
					return json_encode('error');
				}
			}

			return json_encode('success');
		}

		try{
			$pc = Pc::find($id);
			$pc->delete();
		} catch ( Exception $e ) {}

		Session::flash('success-message','Workstation disassembled');
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

			foreach(Pc::separateArray($pc) as $pc)
			{
				try
				{
					Pc::setPcLocation($pc,$room);
				} 
				catch(Exception $e) 
				{
					return $e;
					return json_encode('error');
				}

			}

			return json_encode('success');
		}

		/**
		*
		*	normal request
		*
		*/
		$room = $this->sanitizeString(Input::get('room'));
		$pc = $this->sanitizeString(Input::get('items'));

		foreach(Pc::separateArray($pc) as $pc)
		{
			try
			{
				Pc::setPcLocation($pc,$room);
			} 
			catch(Exception $e) 
			{
				return json_encode('error');
			}

		}

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

			foreach(Pc::separateArray($pc) as $pc)
			{
				try
				{
					Pc::setPcLocation($pc,$room);
				} 
				catch(Exception $e) 
				{
					return json_encode('error');
				}

			}
			return json_encode('success');
		}

		/**
		*
		*	normal request
		*
		*/
		$room = $this->sanitizeString(Input::get('room'));
		$pc = $this->sanitizeString(Input::get('items'));

		foreach(Pc::separateArray($pc) as $pc)
		{
			try
			{
				Pc::setPcLocation($pc,$room);
			} 
			catch(Exception $e) 
			{
				return json_encode('error');
			}

		}

		Session::flash('success-message','Workstation transferred');
		return redirect('workstation/view/transfer');
	}

}
