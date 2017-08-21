<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class ReservationItemsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('reservation.item.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('reservation.item.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$itemtype = $this->sanitizeString(Input::get('itemtype'));
		$brand = $this->sanitizeString(Input::get('brand'));
		$model = $this->sanitizeString(Input::get('model'));
		$included = $this->sanitizeString(Input::get('included'));
		$excluded = $this->sanitizeString(Input::get('excluded'));

		$inventory = Inventory::where('brand',$brand)->where('model',$model)->first();
		if(count($inventory) <= 0){
			Session::flash('error-message','The system cannot find respective brand and model.');
			return redirect()->back()
				->withInput();
		}

		$itemtype = Itemtype::find($itemtype);
		if(count($itemtype) <= 0){
			Session::flash('error-message','The system cannot find respective item type.');
			return redirect()->back()
				->withInput();
		}

		$validator = Validator::make([
				'itemtype' => $itemtype->id,
				'inventory' => $inventory->id
			],Reservationitems::$rules);

		if($validator->fails())
		{
			return redirect()->back()
				->withInput()
				->withErrors($validator);
		}


		$reservationitems = new Reservationitems;
		$reservationitems->itemtype_id = $itemtype->id;
		$reservationitems->inventory_id = $inventory->id;
		$reservationitems->included = $included;
		$reservationitems->excluded = $excluded;
		$reservationitems->status = 'Enabled';
		$reservationitems->save();

		Session::flash('success-message','An item has been added to reservation');
		return redirect('reservation/items/list');
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
		return view('reservation.item.edit')
			->with('reservationitems',Reservationitems::find($id));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$itemtype = $this->sanitizeString(Input::get('itemtype'));
		$brand = $this->sanitizeString(Input::get('brand'));
		$model = $this->sanitizeString(Input::get('model'));
		$included = $this->sanitizeString(Input::get('included'));
		$excluded = $this->sanitizeString(Input::get('excluded'));

		$inventory = Inventory::where('brand',$brand)->where('model',$model)->first();
		if(count($inventory) <= 0){
			Session::flash('error-message','The system cannot find respective brand and model.');
			return redirect()->back()
				->withInput();
		}

		$itemtype = Itemtype::find($itemtype);
		if(count($itemtype) <= 0){
			Session::flash('error-message','The system cannot find respective item type.');
			return redirect()->back()
				->withInput();
		}

		$validator = Validator::make([
				'itemtype' => $itemtype->id,
				'inventory' => $inventory->id
			],Reservationitems::$rules);

		if($validator->fails())
		{
			return redirect()->back()
				->withInput()
				->withErrors($validator);
		}


		$reservationitems = Reservationitems::find($id);
		$reservationitems->itemtype_id = $itemtype->id;
		$reservationitems->inventory_id = $inventory->id;
		$reservationitems->included = $included;
		$reservationitems->excluded = $excluded;
		$reservationitems->status = 'Enabled';
		$reservationitems->save();

		Session::flash('success-message','Item for reservation list updated');
		return redirect('reservation/items/list');
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
			$reservationitems = Reservationitems::find($id);
			$reservationitems->delete();
			return json_encode('success');
		}

		$reservationitems = Reservationitems::find($id);
		$reservationitems->delete();

		Session::flash('success-message','Item for reservation deleted');
		return redirect('reservation/items/list');
	}


}