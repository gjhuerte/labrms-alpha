<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use DB;
use Carbon\Carbon;
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
			if(Input::has('room'))
			{
				$room = $this->sanitizeString(Input::get('room'));
				return json_encode([ 'data' => RoomSchedule::with('faculty')->where('room_id','=',$room)->get() ]);
			}

			return json_encode([ 'data' => RoomSchedule::with('faculty')->where('room_id','=','1')->get() ]);
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
		$days = Input::get('day');

		$validator = Validator::make([ 'Days ' => $days ],[
			'Days' => 'array'
		]);

		if($validator->fails())
		{
			return redirect('schedule/create')
					->withInput()
					->withErrors($validator);
		}

		$subject = $this->sanitizeString(Input::get('subject'));
		$room = $this->sanitizeString(Input::get("room"));
		$semester = $this->sanitizeString(Input::get("semester"));
		$academicyear = $this->sanitizeString(Input::get("academicyear"));
		$timein = $this->sanitizeString(Input::get("timestart"));
		$timeout = $this->sanitizeString(Input::get('timeend'));
		$section = $this->sanitizeString(Input::get("section"));
		$faculty = $this->sanitizeString(Input::get('faculty'));

		DB::beginTransaction();

		foreach($days as $day)
		{

			$validator = Validator::make([
				'Subject' => $subject,
				'Day' => $day,
				'Room' => $room,
				'Semester' => $semester,
				'Faculty' => $faculty,
				'Academic Year' => $academicyear 
			],RoomSchedule::$rules);

			if($validator->fails())
			{
				DB::rollback();
				return redirect('schedule/create')
						->withInput()
						->withErrors($validator);
			}

			$day = $this->sanitizeString($day);

			$roomschedule = new RoomSchedule;
			$roomschedule->room_id = $room;
			$roomschedule->faculty = $faculty;
			$roomschedule->academicyear = $academicyear;
			$roomschedule->semester = $semester;
			$roomschedule->day = $day;
			$roomschedule->timein = Carbon::parse($timein);
			$roomschedule->timeout = Carbon::parse($timeout);
			$roomschedule->subject = $subject;
			$roomschedule->section = $section;
			$roomschedule->save();
		}

		DB::commit();

		Session::flash('success-message','Schedule Created');
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
		$schedule = RoomSchedule::find($id);
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
		$subject = $this->sanitizeString(Input::get('subject'));
		$room = $this->sanitizeString(Input::get("room"));
		$semester = $this->sanitizeString(Input::get("semester"));
		$academicyear = $this->sanitizeString(Input::get("academicyear"));
		$timein = $this->sanitizeString(Input::get("timestart"));
		$timeout = $this->sanitizeString(Input::get('timeend'));
		$section = $this->sanitizeString(Input::get("section"));
		$faculty = $this->sanitizeString(Input::get('faculty'));

		$validator = Validator::make([
			'Subject' => $subject,
			'Room' => $room,
			'Semester' => $semester,
			'Faculty' => $faculty,
			'Academic Year' => $academicyear 
		],RoomSchedule::$updateRules);

		if($validator->fails())
		{
			return redirect("schedule/$id/edit")
					->withInput()
					->withErrors($validator);
		}

		$roomschedule = RoomSchedule::find($id);
		$roomschedule->room_id = $room;
		$roomschedule->faculty = $faculty;
		$roomschedule->academicyear = $academicyear;
		$roomschedule->semester = $semester;
		$roomschedule->timein = Carbon::parse($timein);
		$roomschedule->timeout = Carbon::parse($timeout);
		$roomschedule->subject = $subject;
		$roomschedule->section = $section;
		$roomschedule->save();

		Session::flash('success-message','Updated');
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
		if(Request::ajax())
		{
			$schedule = RoomSchedule::find($id);
			$schedule->delete();
			return json_encode('success');
		}

		$schedule = RoomSchedule::find($id);
		$schedule->delete();

		Session::flash('success-message','Schedule Removed');
		return redirect('schedule');
	}


}
