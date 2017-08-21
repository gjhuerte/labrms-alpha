<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\Room;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class RoomsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		if(Request::ajax())
		{
			return json_encode([
					'data'=> Room::select('name','id','description')->get()
				]);
		}

		$rooms = Room::all();
		return view('room.index')
			->with('rooms',$rooms)
			->with('active_tab','overview');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('room.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$name = $this->sanitizeString(Input::get("name"));
		$description = $this->sanitizeString(Input::get('description'));

		$validator = Validator::make([

				'Name' => $name,
				'Description' => $description

		],Room::$rules);

		if($validator->fails())
		{
			return redirect('room/create')
				->withInput()
				->withErrors($validator);
		}

		$room = new Room;
		$room->name = $name;
		$room->description = $description;
		$room->save();

		Session::flash('success-message','Room information created!');
		return redirect('room');
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
		$room = Room::find($id);
		return view('room.update')
			->with('room',$room);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$id = $this->sanitizeString(Input::get('id'));
		$name = $this->sanitizeString(Input::get("name"));
		$description = $this->sanitizeString(Input::get('description'));

		$validator = Validator::make([

			'Name' => $name,
			'Description' => $description

		],Room::$updateRules);

		if($validator->fails())
		{
			return redirect("room/$id/edit")
				->withInput()
				->withErrors($validator);
		}

		$room = Room::find($id);
		$room->name = $name;
		$room->description = $description;
		$room->save();

		Session::flash('success-message','Room information updated!');
		return redirect('room');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Request::ajax()){
			try{
				$room = Room::find($id);
				$room->delete();
				return json_encode('success');
			} catch( Exception $e ){}
		}

		$room = Room::findOrFail($id);
		$room->delete();
		Session::flash('success-message','Room information deleted');
		return redirect('room');
	}

	public function restoreView(){
		return view('room.restore')
			->with('rooms',Room::onlyTrashed()->get())
			->with('active_tab','restore');
	}

	public function restore($id){
		$room = Room::onlyTrashed()->where('id',$id)->first();
		$room->restore();
		return redirect('room/view/restore');
	}


}
