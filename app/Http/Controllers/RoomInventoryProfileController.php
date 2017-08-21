<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use DB;
use App\RoomInventory;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class RoomInventoryProfileController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function getItemsAssigned($id)
	{
		if(Request::ajax())
		{
			$itemprofile = DB::table('roominventory')->leftJoin('itemprofile','item_id','=','itemprofile.id')
												->leftJoin('inventory','itemprofile.inventory_id','=','inventory.id')
												->whereIn('itemprofile.id',Roominventory::where('room_id',$id)->pluck('item_id'))
												->select('propertynumber','model','brand','roominventory.created_at as created_at')
												->get();
			return json_encode(['data'=>$itemprofile]);	
		}
	}


}
