<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use Auth;
use App\Reservation;
use App\Purpose;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class ReservationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax())
		{
			return json_encode([ 'data' => Reservation::with('user')->get() ]);
		}

		return view('reservation.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('reservation.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$location = $this->sanitizeString(Input::get('location'));
		$purpose = $this->sanitizeString(Input::get('purpose'));
		$dateofuse = $this->sanitizeString(Input::get('dateofuse'));
		$time_start = $this->sanitizeString(Input::get('time_start'));
		$time_end = $this->sanitizeString(Input::get('time_end'));
		$items = Input::get('items');
		$faculty = $this->sanitizeString(Input::get('faculty'));
		$approval = 0;
		$remark = 'pending';

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if purpose is in the list
		|	approved reservation if found
		|
		|--------------------------------------------------------------------------
		|
		*/
		$purpose_info = Purpose::title($purpose)
									->orWhere('title','like','%' . $purpose .'%')
									->first();

		if(count($purpose_info) > 0)
		{
			$approval = 1;
			$remark = 'auto-approved';
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Administrator
		|	if reservation exists, override the existing
		|	check purpose if existing
		|	check current purpose
		|	replace if rank is higher
		|	change remark of old to 'denied due to lower priority'
		|	change old approval to 2
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Auth::user()->accesslevel == 0)
		{

		}

		/*
		|--------------------------------------------------------------------------
		|
		|	check if purpose is user defined
		|	or not on list
		|	if not on list
		|	use description field as purpose
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('contains'))
		{
			$purpose = $this->sanitizeString(Input::get('description'));
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	If the user is faculty, use the users information
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Auth::user()->type == 'faculty')
		{
			$faculty = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	validator ...
		|
		|--------------------------------------------------------------------------
		|
		*/

		$time_start = Carbon::parse($dateofuse . " " . $time_start);
		$time_end = Carbon::parse($dateofuse . " " . $time_end);


		/*
		|--------------------------------------------------------------------------
		|
		| 	Check and replace existing reservation
		|
		|--------------------------------------------------------------------------
		|
		*/
		$reservation = Reservation::hasReserved($time_start,$time_end);
		if( count($reservation)  > 0 && $reservation )
		{
			$reservation->remark = 'Cancelled due to having lower priority';
			$reservation->approval = 2;
			$reservation->save();
		}

		$validator = Validator::make([
			'Location' => $location,
			'Date of use' => $dateofuse,
			'Time started' => $time_start,
			'Time end' => $time_end,
			'Purpose' => $purpose,
			'Faculty-in-charge' => $faculty
		],Reservation::$rules);

		if($validator->fails())
		{
			return redirect('reservation/create')
					->withInput()
					->withErrors($validator);
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	reservation create
		|
		|--------------------------------------------------------------------------
		|
		*/
		$reservation = new Reservation;
		$reservation->user_id = Auth::user()->id;
		$reservation->timein = $time_start;
		$reservation->timeout = $time_end;
		$reservation->purpose = $purpose;
		$reservation->location = $location;
		$reservation->approval = $approval;
		$reservation->remark = $remark;
		$reservation->facultyincharge = $faculty;
		$reservation->save();

		Session::flash('success-message','Reservation Created');
		return redirect('reservation/create');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return view('pagenotfound');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return view('pagenotfound');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return view('pagenotfound');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		return view('pagenotfound');
	}


}
