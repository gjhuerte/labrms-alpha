<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use DB;
use Carbon\Carbon;
use App\RoomSchedule;
use App\RoomReservation;
use App\Reservation;
use App\RoomScheduleView;
use App\Room;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class RoomSchedulingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Request::ajax())
        {
            if(Input::has('room'))
            {
                $room = $this->sanitizeString(Input::get('room'));

                return json_encode([ 'data' => RoomScheduleView::current()->where('room_id','=',$room)->get() ]);

            }

            return json_encode([ 'data' => RoomScheduleView::current()->where('room_id','=','1')->get() ]);   
        }
        
        if(Input::has('room'))
        {
            $room = $this->sanitizeString(Input::get('room'));

            $roomschedule = RoomScheduleView::current()->where('room_id','=',$room)->get();


            return view('schedule.room.index')
                    ->with('roomschedule',$roomschedule)
                    ->with('rooms',Room::all());
        }

        $roomschedule = RoomScheduleView::current()->where('room_id','=','1')->get();

        return view('schedule.room.index')
                ->with('roomschedule',$roomschedule)
                ->with('rooms',Room::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('schedule.room.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect('room/scheduling');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return redirect('room/scheduling');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect('room/scheduling');
    }
}
