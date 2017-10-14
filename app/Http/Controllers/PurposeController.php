<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\Purpose;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class PurposeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax()){
				return json_encode( [
					'data' => Purpose::all()
				] );
		}

		return view('purpose.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('purpose.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$title = $this->sanitizeString(Input::get('title'));
		$description = $this->sanitizeString(Input::get('description'));
		$points = $this->sanitizeString(Input::get('points'));

		$validator = Validator::make([
			'title' => $title,
			'description' => $description,
			'points' => $points
		],Purpose::$rules);

		if($validator->fails()){
			return redirect('purpose/create')
				->withInput()
				->withErrors($validator);
		}

		$purpose = new Purpose;
		$purpose->title = $title;
		$purpose->description = $description;
		$purpose->points = $points;
		$purpose->save();

		Session::flash('success-message','Record has been added to database');
		return redirect('purpose');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return view('purpose.show');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		try{
			return view('purpose.edit')
				->with('purpose',Purpose::find($id));
		} catch( Exception $e ){
			Session::flash('error-message','System has encountered an error');
			return redirect('purpose');
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
		$title = $this->sanitizeString(Input::get('title'));
		$description = $this->sanitizeString(Input::get('description'));
		$points = $this->sanitizeString(Input::get('points'));

		$validator = Validator::make([
			'title' => $title,
			'description' => $description,
			'points' => $points
		],Purpose::$rules);

		if($validator->fails()){
			return redirect('purpose/create')
				->withInput()
				->withErrors($validator);
		}

		$purpose = Purpose::find($id);
		$purpose->title = $title;
		$purpose->description = $description;
		$purpose->points = $points;
		$purpose->save();


		Session::flash('success-message','Purpose Updated');
		return redirect('purpose');
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
				try{
					$purpose = Purpose::find($id);
					$purpose->delete();
					return json_encode('success');
				} catch( Exception $e ){}
		}

		return redirect('purpose');
	}

	public function getAllPurpose()
	{
		$purpose = Purpose::select('title')->get();
		return json_encode($purpose);
	}


}
