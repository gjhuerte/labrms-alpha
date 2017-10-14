<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\Semester;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class SemesterController extends Controller
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
            $semester = Semester::all();

            if(Input::has('academicyear'))
            {
                $academicyear = Input::get("academicyear");
                if($academicyear == 'nearest')
                {
                    $date = Carbon::now()->format('Y-m-d');

                    $semester = Semester::where('datestart','<=',$date)
                                            ->pluck('academicyear');
                }

                $semester = Semester::pluck('academicyear');
            }


            if(Input::has('semester'))
            {
                $sem = Input::get("semester");
                if($sem == 'nearest')
                {
                    $date = Carbon::now()->format('Y-m-d');

                    $semester = Semester::where('datestart','<=',$date)
                                            ->pluck('semester');
                }

                $semester = Semester::pluck('semester');
            }

            return json_encode([
                'data' => $semester
            ]);
        }

        return view('semester.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('semester.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sem = $this->sanitizeString(Input::get('name'));
        $start = $this->sanitizeString(Input::get('start'));
        $academicyear = $this->sanitizeString(Input::get("academicyear"));
        $end = $this->sanitizeString(Input::get('end'));

        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        $validator = Validator::make([
            'Semester Start' => $start,
            'Semester End' => $end,
            'Semester' => $sem
        ],Semester::$rules);

        if($validator->fails())
        {
            return redirect('semester/create')
                    ->withInput()
                    ->withErrors($validator);
        }

        $start = $start->format('Y-m-d');
        $end = $end->format('Y-m-d');

        $semester = new Semester;
        $semester->semester = $sem;
        $semester->academicyear = $academicyear;
        $semester->datestart = $start;
        $semester->dateend = $end;
        $semester->save();

        Session::flash('success-message','Semester Added');
        return redirect('semester');
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
        $semester = Semester::find($id);
        return view('semester.update')
                ->with('semester',$semester);
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
        $sem = $this->sanitizeString(Input::get('name'));
        $academicyear = $this->sanitizeString(Input::get('academicyear'));
        $start = $this->sanitizeString(Input::get('start'));
        $end = $this->sanitizeString(Input::get('end'));

        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        $validator = Validator::make([
            'Semester Start' => $start,
            'Semester End' => $end,
            'Semester' => $sem
        ],Semester::$updateRules);

        if($validator->fails())
        {
            return redirect("semester/$id/edit")
                    ->withInput()
                    ->withErrors($validator);
        }

        $semester = Semester::find($id);
        $semester->semester = $sem;
        $semester->academicyear = $academicyear;
        $semester->datestart = $start->format('Y-m-d');
        $semester->dateend = $end->format('Y-m-d');
        $semester->save();

        Session::flash('success-message','Semester Updated');
        return redirect('semester');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if(Request::ajax())
        {
            $semester = Semester::find($id);
            $semester->delete();
            return json_encode('success');
        }

        $semester = Semester::find($id);
        $semester->delete();

        Session::flash('success-message','Semester Removed');
        return redirect('semester');
    }
}
