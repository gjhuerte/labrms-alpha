<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\RoomSchedule;
use App\Room;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class LaboratoryScheduleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax())
		{
			return json_encode([ 'data' => RoomSchedule::all() ]);
		}

		return view('schedule.index')
				->with('rooms',Room::all());
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('schedule.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return Input::all();
		return redirect('schedule');
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
		$schedule = Schedule::find($id);
		if(isset($schedule))
		{
			return view('schedule.edit')
					->with('schedule',$schedule);
		}
		else
		{
			Session::flash('error-message','Error occurred while processing your data.');
			return redirect('schedule');
		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return redirect('schedule');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

		return redirect('schedule');
	}


}
