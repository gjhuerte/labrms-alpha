<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\ItemType;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class ItemTypesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax())
		{
			return json_encode([
					'data' => ItemType::select('id','name','description','category')->get()
				]);
		}

		return view('item.type.index');
	}

	public function create()
	{
		return view('item.type.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$name = $this->sanitizeString(Input::get('name'));
		$description = $this->sanitizeString(Input::get('description'));
		$category = $this->sanitizeString(Input::get('category'));

		$validator = Validator::make([
			'name' => $name,
			'description' => $description
		],ItemType::$rules);

		if($validator->fails())
		{
			return redirect('item/type/create')
				->withInput()
				->withErrors($validator);
		}

		ItemType::createRecord($name,$description,$category);

		Session::flash('success-message','Item type created');
		return redirect('item/type');
	}

	public function edit($id)
	{
		$itemtype = Itemtype::find($id);
		return view('item.type.edit')
			->with('itemtype',$itemtype);
	}

	public function update($id)
	{
		$name = $this->sanitizeString(Input::get('name'));
		$description = $this->sanitizeString(Input::get('description'));
		$category = $this->sanitizeString(Input::get('category'));

		$validator = Validator::make([
			'name' => $name,
			'description' => $description
		],Itemtype::$updateRules);

		if($validator->fails())
		{
			return redirect("item/type/$id/edit")
				->withInput()
				->withErrors($validator);
		}

		$itemtype = Itemtype::find($id);
		$itemtype->name = $name;
		$itemtype->description = $description;
		$itemtype->category = $category;
		$itemtype->save();

		Session::flash('success-message','Item type updated');
		return redirect('item/type');
	}

	public function destroy($id)
	{

		if(Request::ajax()){
			try{

				$itemtype = Itemtype::find($id);
				$itemtype->delete();
				return json_encode('success');
			}catch( Exception $e ){}
		}

		try{

			$itemtype = Itemtype::find($id);
			$itemtype->delete();
		}catch( Exception $e ){
			Session::flash('error-message','Item type does not exists');
		}

		Session::flash('success-message','Item type deleted');
		return redirect('item/type/');

	}

	public function restoreView()
	{
		$itemtype = Itemtype::onlyTrashed()->get();
		return view('item.type.restore-view')
			->with('itemtype',$itemtype);
	}

	public function restore($id)
	{
		$itemtype = Itemtype::onlyTrashed()->where('id',$id)->first();
		$itemtype->restore();

		Session::flash('success-message','Item type restored');
		return redirect('item/type/view/restore');
	}

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
