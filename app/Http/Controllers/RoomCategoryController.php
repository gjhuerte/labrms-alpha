<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\RoomCategory;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class RoomCategoryController extends Controller
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
                'data' => RoomCategory::all()
            ]);
        }

        return view('room.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('room.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        $category = $this->sanitizeString(Input::get('name'));

        $validator = Validator::make([
            'Category' => $category
        ],RoomCategory::$rules);

        if($validator->fails())
        {
            return redirect('room/category')
                    ->withInput()
                    ->withErrors($validator);
        }

        $roomcategory = new RoomCategory;
        $roomcategory->category = $category;
        $roomcategory->save();

        Session::flash('success-message','Room Category added');
        return redirect('room/category');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('room.category.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('room.category.edit');
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
            $category = $this->sanitizeString(Input::get('name'));
            $id = $this->sanitizeString($id);

            $validator = Validator::make([
                'Category' => $category
            ],RoomCategory::$updateRules);

            if($validator->fails())
            {
                return redirect('room/category')
                        ->withInput()
                        ->withErrors($validator);
            }

            $roomcategory = RoomCategory::find($id);
            $roomcategory->category = $category;
            $roomcategory->save();

            return json_encode('success');
        }

        $category = $this->sanitizeString(Input::get('name'));

        $validator = Validator::make([
            'Category' => $category
        ],RoomCategory::$rules);

        if($validator->fails())
        {
            return redirect('room/category')
                    ->withInput()
                    ->withErrors($validator);
        }

        $roomcategory = RoomCategory::find($category);
        $roomcategory->category = $category;
        $roomcategory->save();

        Session::flash('success-message','Room Category updated');
        return redirect('room/category');
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
            $roomcategory = RoomCategory::find($id);
            $roomcategory->delete();
            return json_encode('success');
        }

        $roomcategory = RoomCategory::find($id);
        $roomcategory->delete();

        Session::flash('success-message','Room Category removed');
        return redirect('room/category');
    }
}
