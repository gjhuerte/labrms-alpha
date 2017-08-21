<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\ItemType;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class ItemTypesAjaxController extends Controller {

	public function getAllItemTypes()
	{
		if(Request::ajax())
		{
			$workstation = Input::get('workstation');
			if($workstation === 'workstation'){
				$itemtype = ItemType::where('name','!=','System Unit')
									->where('name','!=','Display')
									->where('name','!=','AVR')
									->where('name','!=','Keyboard')
									->get();
			}else{
				$itemtype = ItemType::all();
			}
			return json_encode($itemtype);
		}
	}

	public function getAllFieldsFromGivenID($id)
	{
		$ret_val = "";
		if(Request::ajax())
		{
			try{
				$itemtype = ItemType::find($id);
				$ret_val = explode(',',$itemtype->field);
			} catch (Exception $e) {
				$ret_val = "error";
			}

		}
			return json_encode($ret_val);
	}

	public function getItemTypesForEquipmentInventory()
	{
		if(Request::ajax())
		{
			$itemtype = ItemType::where('category','=','equipment')->get();
			return json_encode($itemtype);
		}

	}

	public function getItemTypesForSuppliesInventory()
	{
		if(Request::ajax())
		{
			$itemtype = ItemType::where('category','=','supply')->get();
			return json_encode($itemtype);
		}

	}


}
