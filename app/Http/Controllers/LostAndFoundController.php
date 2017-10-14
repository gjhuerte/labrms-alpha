<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Validator;
use Session;
use DB;
use App\LostAndFoundItems;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class LostAndFoundController extends Controller
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
            return json_encode([ 'data' => LostAndFoundItems::all() ]);
        }

        return view("lostandfound.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("lostandfound.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $identifier = $this->sanitizeString(Input::get('identifier'));
        $description = $this->sanitizeString(Input::get('description'));
        $datefound = $this->sanitizeString(Input::get("datefound"));

        $datefound = Carbon::parse($datefound);

        $validator = Validator::make([
            'Identifier' => $identifier,
            'Description' => $description,
            'Date Found' => $datefound
        ],LostAndFoundItems::$rules);

        if($validator->fails())
        {
            return redirect('lostandfound/create')
                    ->withInput()
                    ->withErrors($validator);
        }

        $lostandfounditems = new LostAndFoundItems;
        $lostandfounditems->identifier = $identifier;
        $lostandfounditems->description = $description;
        $lostandfounditems->datefound = $datefound;
        $lostandfounditems->save();

        Session::flash('success-message','Item added to lost and found');
        return redirect('lostandfound');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('lostandfound.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lostandfound = LostAndFoundItems::find($id);
        return view('lostandfound.update')
                ->with('lostandfound',$lostandfound);
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

        if(Request::ajax())
        {
            if(Input::has('claim'))
            {
                $claimant = $this->sanitizeString(Input::get('claimant'));

                $dateclaimed = Carbon::now();
                $status = 'claimed';

                $validator = Validator::make([
                    'Claimant' => $claimant,
                    'ID' => $id
                ],LostAndFoundItems::$claimRules);

                if($validator->fails())
                {
                    return json_encode('error');
                }

                $lostandfounditems = LostAndFoundItems::find($id);
                $lostandfounditems->status = $status;
                $lostandfounditems->dateclaimed = $dateclaimed;
                $lostandfounditems->claimant = $claimant;
                $lostandfounditems->save();

                return json_encode('success');
            }
        }

        $identifier = $this->sanitizeString(Input::get('identifier'));
        $description = $this->sanitizeString(Input::get('description'));
        $datefound = $this->sanitizeString(Input::get("datefound"));

        $datefound = Carbon::parse($datefound);

        $validator = Validator::make([
            'Identifier' => $identifier,
            'Description' => $description,
            'Date Found' => $datefound
        ],LostAndFoundItems::$updateRules);

        if($validator->fails())
        {
            return redirect("lostandfound/$id/edit")
                    ->withInput()
                    ->withErrors($validator);
        }

        $lostandfounditems = LostAndFoundItems::find($id);
        $lostandfounditems->identifier = $identifier;
        $lostandfounditems->description = $description;
        $lostandfounditems->datefound = $datefound;
        $lostandfounditems->save();

        Session::flash('success-message','Item information updated');
        return redirect('lostandfound');
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
            $lostandfounditems = LostAndFoundItems::find($id);
            $lostandfounditems->delete();
            return json_encode('success');
        }

        $lostandfounditems = LostAndFoundItems::find($id);
        $lostandfounditems->delete();

        Session::flash('success-message','Item removed');
        return redirect('lostandfound');
    }
}
