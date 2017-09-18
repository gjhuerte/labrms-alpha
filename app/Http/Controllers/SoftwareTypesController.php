<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\SoftwareType;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class SoftwareTypesController extends Controller
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
            return json_encode([
                'data' => SoftwareType::all()
            ]);
        }

        return view('software.type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('software.type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        $type = $this->sanitizeString(Input::get('name'));

        $validator = Validator::make([
            'Type' => $type
        ],SoftwareType::$rules);

        if($validator->fails())
        {
            return redirect('software/type')
                    ->withInput()
                    ->withErrors($validator);
        }

        $softwaretypes = new SoftwareType;
        $softwaretypes->type = $type;
        $softwaretypes->save();

        Session::flash('success-message','Software Type added');
        return redirect('software/type');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('software.type.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('software.type.edit');
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
            $type = $this->sanitizeString(Input::get('name'));
            $id = $this->sanitizeString($id);

            $validator = Validator::make([
                'Category' => $type
            ],SoftwareType::$updateRules);

            if($validator->fails())
            {
                return redirect('software/type')
                        ->withInput()
                        ->withErrors($validator);
            }

            $softwaretypes = SoftwareType::find($id);
            $softwaretypes->type = $type;
            $softwaretypes->save();

            return json_encode('success');
        }

        $type = $this->sanitizeString(Input::get('name'));

        $validator = Validator::make([
            'Category' => $type
        ],SoftwareType::$rules);

        if($validator->fails())
        {
            return redirect('software/type')
                    ->withInput()
                    ->withErrors($validator);
        }

        $softwaretypes = SoftwareType::find($type);
        $softwaretypes->type = $type;
        $softwaretypes->save();

        Session::flash('success-message','Software Type updated');
        return redirect('software/type');
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
            $softwaretypes = SoftwareType::find($id);
            $softwaretypes->delete();
            return json_encode('success');
        }

        $softwaretypes = SoftwareType::find($id);
        $softwaretypes->delete();

        Session::flash('success-message','Software Type removed');
        return redirect('software/type');
    }
}
