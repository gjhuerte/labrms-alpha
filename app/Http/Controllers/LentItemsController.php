<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Validator;
use Session;
use DB;
use App;
use App\LendLog;
use App\ItemProfile;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class LentItemsController extends Controller
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
            return json_encode([ 'data' => LendLog::with('itemprofile')->get() ]);
        }

        return view('lend.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $reservation = '';

        if(Input::has('reservation'))
        {
            $id = $this->sanitizeString(Input::get('reservation'));

            $reservation = App\Reservation::with('user')->find($id);
        }

        return view('lend.create')
                ->with('date',Carbon::now()->toFormattedDateString())
                ->with('reservation',$reservation);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $items = Input::get("item");

        $reservation = "";

        if(Input::has('reservation'))
        {
            $id = $this->sanitizeString(Input::get('reservation'));

            $reservation = App\Reservation::with('user')->find($id);
        }

        $validator = Validator::make([
            'Item' => $items
        ],LendLog::$itemRule);

        if ($validator->fails()) {
            return redirect('lend/create')
                    ->with('reservation',$reservation)
                    ->withInput()
                    ->withErrors($validator);
        }

        if(count($items) <= 0)
        {
            return redirect('lend/create')
                    ->withInput()
                    ->withErrors(['Item must have a value']);
        }

        /*
        |--------------------------------------------------------------------------
        |
        |   instantiate
        |
        |--------------------------------------------------------------------------
        |
        */
        $firstname = $this->sanitizeString(Input::get('firstname'));
        $middlename = $this->sanitizeString(Input::get('middlename'));
        $lastname = $this->sanitizeString(Input::get('lastname'));
        $courseyearsection = $this->sanitizeString(Input::get('courseyearsection'));
        $facultyincharge = $this->sanitizeString(Input::get("name"));
        $location = $this->sanitizeString(Input::get('location'));


        DB::beginTransaction();

        foreach($items as $item)
        {


            $validator = Validator::make([
                'First Name' => $firstname,
                'Course Year and Section' => $courseyearsection,
                'Last Name' => $lastname,
                'Item' => $item
            ],LendLog::$rules,[ 
                'Item.exists' => "The :attribute $item is invalid" 
            ]);

            if ($validator->fails()) {
                DB::rollback();
                return redirect('lend/create')
                        ->withInput()
                        ->withErrors($validator);
            }

            $item = $this->sanitizeString($item);

            $item = ItemProfile::propertyNumber($item)->first();

            /*
            |--------------------------------------------------------------------------
            |
            |   save
            |
            |--------------------------------------------------------------------------
            |
            */
            $lendlog = new LendLog;
            $lendlog->item_id = $item->id;
            $lendlog->firstname = $firstname;
            $lendlog->middlename = $middlename;
            $lendlog->lastname = $lastname;
            $lendlog->courseyearsection = $courseyearsection;
            $lendlog->facultyincharge = $facultyincharge;
            $lendlog->timein = Carbon::now();
            $lendlog->location = $location;
            $lendlog->save();
        }

        DB::commit();

        Session::flash('success-message','Item lent');
        return redirect('lend');
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
        //
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
            $id = $this->sanitizeString(Input::get('id'));

            $lendlog = LendLog::find($id);
            $lendlog->timeout = Carbon::now();
            $lendlog->save();

            if(count($lendlog) > 0)
            {
                return json_encode('success');
            }

            return json_encode('error');
        }

        Session::flash('success-message','item returned');
        return redirect('lend');
    }
}
