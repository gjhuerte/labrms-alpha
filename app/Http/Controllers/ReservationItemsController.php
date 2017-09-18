<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\ReservationItems;
use App\ItemType;
use App\ItemProfile;
use App\Inventory;
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

		/*
		|--------------------------------------------------------------------------
		|
		| 	validate inventory
		|
		|--------------------------------------------------------------------------
		|
		*/
		$inventory = Inventory::where('brand',$brand)->where('model',$model)->first();
		if(count($inventory) <= 0)
		{
			Session::flash('error-message','The system cannot find respective brand and model.');
			return redirect()->back()
				->withInput();
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	validate itemtype
		|
		|--------------------------------------------------------------------------
		|
		*/
		$itemtype = ItemType::find($itemtype);
		if(count($itemtype) <= 0)
		{
			Session::flash('error-message','The system cannot find respective item type.');
			return redirect()->back()
				->withInput();
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	validate 
		|
		|--------------------------------------------------------------------------
		|
		*/
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

		if($included != '')
		{
			foreach(explode(',',$included) as $propertynumber)
			{
				$validator = Validator::make(['Property Number' => $propertynumber],[
					'Property Number' => 'exists:itemprofile,propertynumber'
				],[ 'Property Number.exists' => "The :attribute $propertynumber must exists" ]);

				if($validator->fails())
				{
					return redirect("reservation/items/list/$id/edit")
						->withInput()
						->withErrors($validator);
				}
			}
		}

		if($excluded != '')
		{
			foreach(explode(',',$excluded) as $propertynumber)
			{
				$validator = Validator::make(['Property Number' => $propertynumber],[
					'Property Number' => 'exists:itemprofile,propertynumber'
				],[ 'Property Number.exists' => "The :attribute $propertynumber must exists" ]);

				if($validator->fails())
				{
					return redirect('reservation/items/list/create')
						->withInput()
						->withErrors($validator);
				}
			}
		}

		$reservationitems = new ReservationItems;
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
			->with('reservationitems',ReservationItems::find($id));
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

		/*
		|--------------------------------------------------------------------------
		|
		| 	validate inventory
		|
		|--------------------------------------------------------------------------
		|
		*/
		$inventory = Inventory::where('brand',$brand)->where('model',$model)->first();
		if(count($inventory) <= 0)
		{
			Session::flash('error-message','The system cannot find respective brand and model.');
			return redirect()->back()
				->withInput();
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	validate itemtype
		|
		|--------------------------------------------------------------------------
		|
		*/
		$itemtype = ItemType::find($itemtype);
		if(count($itemtype) <= 0)
		{
			Session::flash('error-message','The system cannot find respective item type.');
			return redirect()->back()
				->withInput();
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	validate
		|
		|--------------------------------------------------------------------------
		|
		*/
		$validator = Validator::make([
				'itemtype' => $itemtype->id,
				'inventory' => $inventory->id
			],Reservationitems::$rules);

		if($validator->fails())
		{
			return redirect('reservation/items/list/create')
				->withInput()
				->withErrors($validator);
		}

		if($included != '')
		{
			foreach(explode(',',$included) as $propertynumber)
			{
				$validator = Validator::make(['Property Number' => $propertynumber],[
					'Property Number' => 'exists:itemprofile,propertynumber'
				],[ 'Property Number.exists' => "The :attribute $propertynumber must exists" ]);

				if($validator->fails())
				{
					return redirect("reservation/items/list/$id/edit")
						->withInput()
						->withErrors($validator);
				}
			}
		}

		if($excluded != '')
		{
			foreach(explode(',',$excluded) as $propertynumber)
			{
				$validator = Validator::make(['Property Number' => $propertynumber],[
					'Property Number' => 'exists:itemprofile,propertynumber'
				],[ 'Property Number.exists' => "The :attribute $propertynumber must exists" ]);

				if($validator->fails())
				{
					return redirect("reservation/items/list/$id/edit")
						->withInput()
						->withErrors($validator);
				}
			}
		}

		$reservationitems = ReservationItems::find($id);
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
			$reservationitems = ReservationItems::find($id);
			$reservationitems->delete();
			return json_encode('success');
		}

		$reservationitems = ReservationItems::find($id);
		$reservationitems->delete();

		Session::flash('success-message','Item for reservation deleted');
		return redirect('reservation/items/list');
	}


	public function getAllReservationItemList()
	{
		if(Request::ajax())
		{
			$reservationitems = ReservationItems::leftJoin('inventory','inventory.id','=','reservationitems.inventory_id')->leftJoin('itemtype','itemtype.id','=','reservationitems.itemtype_id')->select('reservationitems.id as id','itemtype.name as name','inventory.model as model','inventory.brand as brand','reservationitems.included as included','reservationitems.excluded as excluded','reservationitems.status as status')->get();
			return json_encode(['data'=>$reservationitems]);
		}
	}
	/*
	|--------------------------------------------------------------------------
	|
	| 	Update status of reservation item
	|
	|--------------------------------------------------------------------------
	|
	*/
	public function updateReservationItemListStatus($id)
	{
		$reservationitems = ReservationItems::find($id);
		if(count($reservationitems) > 0)
		{
			if($reservationitems->status == 'Disabled')
			{
				$reservationitems->status = 'Enabled';
			}
			else
			{
				$reservationitems->status = 'Disabled';	
			} 
			$reservationitems->save();
			return json_encode('success');
		}
		return json_encode('error');
	}

	public function getAllReservationItemType()
	{
		$reservationitems = ReservationItems::with('itemtype')
												->enabled()
												->get()
												->unique('itemtype.name');
		return json_encode($reservationitems);
	}

	public function getAllReservationItemBrand()
	{
		$itemtype = $this->sanitizeString(Input::get('itemtype'));
		$itemtype = Itemtype::where('name',$itemtype)->select('id')->first();
		if(count($itemtype) > 0)
		{
			$reservationitems = Inventory::where('itemtype_id',$itemtype->id)->select('brand')->get();
			return json_encode($reservationitems);
		}
	}

	public function getAllReservationItemModel()
	{
		$brand = $this->sanitizeString(Input::get('brand'));
		$model = Inventory::where('brand',$brand)->select('model')->get();
		return json_encode($model);
	}

	public function getAllReservationItemPropertyNumber()
	{
		$propertynumber = $this->sanitizeString(Input::get('propertynumber'));
		$itemtype = $this->sanitizeString(Input::get('itemtype'));
		$itemtype = ItemType::where('name',$itemtype)
								->select('id')
								->first();
		if(count($itemtype) > 0)
		{
			$brand = $this->sanitizeString(Input::get('brand'));
			$model = $this->sanitizeString(Input::get('model'));
			$inventory = Inventory::where('brand',$brand)
									->where('model',$model)
									->first();
			if(count($inventory) > 0){
				$propertynumber = ItemProfile::where('inventory_id',$inventory->id)
												->whereNotIn('propertynumber',explode(',',$propertynumber))
												->select('propertynumber')
												->get();
				return json_encode($propertynumber);
			}
		}
	}


}
