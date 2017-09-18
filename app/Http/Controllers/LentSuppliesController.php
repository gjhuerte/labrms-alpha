<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Validator;
use Session;
use DB;
use App\SupplyLendLog;
use App\Supply;
use App\SupplyHistory;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class LentSuppliesController extends Controller
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
            return json_encode([ 'data' => SupplyLendLog::with('supply')->get() ]);
        }

        return view('lend.supply.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lend.supply.create')
                ->with('date',Carbon::now()->toFormattedDateString());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $supplies = Input::get("supply");

        $validator = Validator::make([
            'Supply' => $supplies
        ],SupplyLendLog::$itemRule);

        if ($validator->fails()) {
            return redirect('lend/supply/create')
                    ->withInput()
                    ->withErrors($validator);
        }

        if(count($supplies) <= 0)
        {
            return redirect('lend/supply/create')
                    ->withInput()
                    ->withErrors(['Supply must have a value']);
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

        foreach($supplies as $supply)
        {
        	$item = $this->sanitizeString($supply['item']);
        	$type = $this->sanitizeString($supply['type']);
        	$quantity = $this->sanitizeString($supply['quantity']);
        	$rules = false;

            $validator = Validator::make([
                'First Name' => $firstname,
                'Course Year and Section' => $courseyearsection,
                'Last Name' => $lastname,
                'Supply' => $item,
                'Type' => $type
            ],SupplyLendLog::$rules,[ 
                'Supply.exists' => "The :attribute $item is invalid" ,
                'Type.exists' => "The :attribute $type is invalid" 
            ]);

            if ($validator->fails()) {
                DB::rollback();
                return redirect('lend/supply/create')
                        ->withInput()
                        ->withErrors($validator);
            }


            $supply = Supply::brand($supply['item'])->itemType($supply['type'])->first();

            if($supply->quantity - $quantity <= 0)
            {
                DB::rollback();
                return redirect('lend/supply/create')
                        ->withInput()
                        ->withErrors(['No more supplies to release']);
            }

            /*
            |--------------------------------------------------------------------------
            |
            |   save
            |
            |--------------------------------------------------------------------------
            |
            */

            $supplylendlog = new SupplyLendLog;
            $supplylendlog->supply_id = $supply->id;
            $supplylendlog->firstname = $firstname;
            $supplylendlog->middlename = $middlename;
            $supplylendlog->lastname = $lastname;
            $supplylendlog->courseyearsection = $courseyearsection;
            $supplylendlog->facultyincharge = $facultyincharge;
            $supplylendlog->timein = Carbon::now();
            $supplylendlog->location = $location;
            $supplylendlog->quantity = $quantity;
            $supplylendlog->save();

            $supply->quantity = $supply->quantity - $quantity;
            $supply->save();

            $name = $firstname . " " . $middlename . " " . $lastname;
            $purpose = 'Suppply lent to ' . $name . ' on ' . Carbon::now()->toFormattedDateString() . '. ';

            Supply::release($supply->id,$quantity,$purpose,$name);
        }

        DB::commit();

        Session::flash('success-message','Supplies lent');
        return redirect('lend/supply');
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

            $supplylendlog = SupplyLendLog::find($id);

            $firstname = $supplylendlog->firstname;
            $middlename = $supplylendlog->middlename;
            $lastname = $supplylendlog->lastname;
            $supply_id = $supplylendlog->supply_id;
            $quantity = $supplylendlog->quantity;

            $supplylendlog->timeout = Carbon::now();
            $supplylendlog->save();

            $name = $firstname . " " . $middlename . " " . $lastname;
            $purpose = $quantity . ' supply/supplies returned from ' . $name . ' on ' . Carbon::now()->toFormattedDateString() . '. ';

            Supply::add($supply_id,$quantity);
            SupplyHistory::createRecord($supply_id,$quantity,$purpose,$name);

            if(count($supplylendlog) > 0)
            {
                return json_encode('success');
            }

            return json_encode('error');
        }

        Session::flash('success-message','supplies returned');
        return redirect('lend/supply');
    }
}
