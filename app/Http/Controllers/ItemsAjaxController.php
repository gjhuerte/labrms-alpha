<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Inventory;
use Validator;
use App\Receipt;
use App\ItemProfile;
use App\Pc;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class ItemsAjaxController extends Controller {

	/**
	*
	*	get receipt based on inventory
	*	uses ajax request
	*	@param inventory id
	*	@return receipt
	*
	*/
	public function getAllReceipt(){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			$id = $this->sanitizeString(Input::get('id'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	if id is not valid
			|
			|--------------------------------------------------------------------------
			|
			*/
			if($id == -1)
			{
				return json_encode('error');
			}
			else
			{
				$receipt = Receipt::where('inventory_id','=',$id)->select('number','id')->get();
				return $receipt;
			}
		}
	}

	/**
	*
	*	get item brand
	*	uses ajax request
	*	@param itemtype
	*	@return item brand
	*
	*/
	public function getItemBrands(){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			$itemtype = $this->sanitizeString(Input::get('itemtype'));
			if(count($itemtype) > 0)
			{
				$brands = Inventory::where('itemtype_id',$itemtype)->select('brand')->get();
			}
			else
			{
				$brands = Inventory::select('brand')->get();
			}


			/*
			|--------------------------------------------------------------------------
			|
			| 	return all brand
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode($brands);
		}
	}

	/**
	*
	*	get item model
	*	uses ajax request
	*	@param brand
	*	@return item model
	*
	*/
	public function getItemModels(){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			$brand = $this->sanitizeString(Input::get('brand'));
			if(count($brand) > 0)
			{
				$models = Inventory::where('brand',$brand)->select('model')->get();
			}
			else
			{
				$models = Inventory::select('model')->get();
			}

			/*
			|--------------------------------------------------------------------------
			|
			| 	return all models
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode($models);
		}
	}

	/**
	*
	*	get item brand
	*	uses ajax request
	*	@param itemtype
	*	@return item brand
	*
	*/
	public function getPropertyNumberOnServer(){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{

			$model = $this->sanitizeString(Input::get('model'));
			$brand = $this->sanitizeString(Input::get('brand'));
			$itemtype = $this->sanitizeString(Input::get('itemtype'));
			if($model == '' || $brand == '')
			{
				return json_encode('');
			}


			/*
			|--------------------------------------------------------------------------
			|
			| 	get inventory information	
			|
			|--------------------------------------------------------------------------
			|
			*/
			$inventory = Inventory::where('model',$model)
									->where('brand',$brand)
									->where('itemtype_id',$itemtype)
									->select('id')
									->first();

			/*
			|--------------------------------------------------------------------------
			|
			| 	get property number of item
			|
			|--------------------------------------------------------------------------
			|
			*/
			$propertynumber = ItemProfile::where('inventory_id',$inventory->id)
											->where('location','Server')
											->select('propertynumber')
											->get();


			/*
			|--------------------------------------------------------------------------
			|
			| 	if item does not exists
			|
			|--------------------------------------------------------------------------
			|
			*/
			if(count($brand) == 0  && count($itemtype) == 0)
			{
				return json_encode('');
			}

			return json_encode($propertynumber);

		}
	}

	/**
	*
	*	get unassigned system unti
	*	uses ajax request
	*	@return lists of property number
	*
	*/
	public function getUnassignedSystemUnit(){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			return ItemProfile::getUnassignedPropertyNumber('System Unit');
		}
	}

	/**
	*
	*	get unassigned monitor
	*	uses ajax request
	*	@return lists of property number
	*
	*/
	public function getUnassignedMonitor(){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			return ItemProfile::getUnassignedPropertyNumber('Display');
		}
	}

	/**
	*
	*	get unassigned avr
	*	uses ajax request
	*	@return lists of property number
	*
	*/
	public function getUnassignedAVR(){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			return ItemProfile::getUnassignedPropertyNumber('AVR');
		}
	}

	/**
	*
	*	get unassigned keyboard
	*	uses ajax request
	*	@return lists of property number
	*
	*/
	public function getUnassignedKeyboard(){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			return ItemProfile::getUnassignedPropertyNumber('Keyboard');
		}
	}

	/**
	*
	*	get all propertynumber
	*	uses ajax request
	*	@return lists of property number
	*
	*/
	public function getAllPropertyNumber(){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			return json_encode(Itemprofile::pluck('propertynumber'));
		}
	}

	/**
	*
	*	get item information
	*	uses ajax request
	*	@param propertynumber
	*	@return item information
	*
	*/
	public function getStatus($propertynumber){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			try{
				$item = ItemProfile::with('inventory.itemtype')
										->propertyNumber($propertynumber)
										->first();

				/*
				|--------------------------------------------------------------------------
				|
				| 	check if item exists
				|
				|--------------------------------------------------------------------------
				|
				*/
				if(count($item) > 0) 
				{
					return json_encode($item);
				} 
				else 
				{
					return json_encode('error');
				}

			} 
			catch ( Exception $e ) 
			{
				return json_encode('error');
			}
		}
	}

	/**
	*
	*	get list of monitor
	*	uses ajax request
	*	@param propertynumber
	*	@return lists of property number
	*
	*/
	public function getMonitorList()
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			$monitor = $this->sanitizeString(Input::get('term'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	get lists of unassembled monitor
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
				ItemProfile::unassembled()
							->whereHas('inventory',function($query){
								$query->whereHas('itemtype',function($query){
									$query->where('name','=','Display');
								});
							})
							->where('propertynumber','like','%'.$monitor.'%')
							->pluck('propertynumber')
			);
		}
	}

	/**
	*
	*	get list of keyboard
	*	uses ajax request
	*	@param propertynumber
	*	@return lists of property number
	*
	*/
	public function getKeyboardList()
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			$keyboard = $this->sanitizeString(Input::get('term'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	get keyboard not in pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
				ItemProfile::unassembled()
							->whereHas('inventory',function($query){
								$query->whereHas('itemtype',function($query){
									$query->where('name','=','Keyboard');
								});
							})
							->where('propertynumber','like','%'.$keyboard.'%')
							->pluck('propertynumber')
			);
		}
	}

	/**
	*
	*	get list of avr
	*	uses ajax request
	*	@param propertynumber
	*	@return lists of property number
	*
	*/
	public function getAVRList()
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			$avr = $this->sanitizeString(Input::get('term'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	get avr not in pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
				ItemProfile::unassembled()
							->whereHas('inventory',function($query){
								$query->whereHas('itemtype',function($query){
									$query->where('name','=','AVR');
								});
							})
							->where('propertynumber','like','%'.$avr.'%')
							->pluck('propertynumber')
			);
		}
	}

	/**
	*
	*	get list of system unit
	*	uses ajax request
	*	@param propertynumber
	*	@return lists of property number
	*
	*/
	public function getSystemUnitList()
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			$systemunit = $this->sanitizeString(Input::get('term'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	get system unit not in pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
				Itemprofile::unassembled()
							->whereHas('inventory',function($query){
								$query->whereHas('itemtype',function($query){
									$query->where('name','=','System Unit');
								});
							})
							->where('propertynumber','like','%'.$systemunit.'%')
							->pluck('propertynumber')
			);
		}
	}

	/**
	*
	*	chec if inventory is existing
	*	uses ajax request
	*	@param item type
	*	@param brand
	*	@param model
	*	@return inventory information
	*
	*/
	public function checkifexisting($itemtype,$brand,$model)
	{
		$itemtype = $this->sanitizeString($itemtype);
		$brand = $this->sanitizeString($brand);
		$model = $this->sanitizeString($model);


		/*
		|--------------------------------------------------------------------------
		|
		| 	get inventory information
		|
		|--------------------------------------------------------------------------
		|
		*/
		$inventory = Inventory::brand($brand)
								->model($model)
								->type($itemtype)
								->first();
								
		if(count($inventory) > 0)
		{
			return json_encode($inventory);
		} 
		else 
		{
			return json_encode('error');
		}
	}

	public function getItemInformation($propertynumber)
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Request::ajax())
		{
			$propertynumber = $this->sanitizeString($propertynumber);
			/*
			|--------------------------------------------------------------------------
			|
			| 	check if item is linked to pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			$item = Pc::isPc($propertynumber);

			/*
			|--------------------------------------------------------------------------
			|
			| 	if not linked to pc, get the item profile information
			|
			|--------------------------------------------------------------------------
			|
			*/
			if(is_null($item) || $item == null)
			{
				$item = ItemProfile::propertyNumber($propertynumber)->first();
			}
			else
			{
				$item = Pc::with('systemunit')
							->with('keyboard')
							->with('avr')
							->with('monitor')
							->find($item->id);
			}

			if(count($item) == 0)
			{
				return json_encode('error');
			}	

			return json_encode($item);
		}
	}


}
