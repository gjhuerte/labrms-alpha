<?php

namespace App;

use DB;
use App\SupplyHistory;
use App\ItemType;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Supply extends \Eloquent{
	//Database driver
	/*
		1 - Eloquent (MVC Driven)
		2 - DB (Directly query to SQL database, no model required)
	*/
	//The table in the database used by the model.


	//The table in the database used by the model.
	protected $table = 'supply';
	public $fillable = ['brand','unit','quantity'];
	public $timestamps = true;
	//Validation rules!
	protected $primaryKey = 'id';
	public static $rules = array(
		'Requisition and Issue Slip' => 'required',
		'Item Type' => 'required|exists:Itemtype,id',
		'Brand' => 'min:2|max:100',
		'Unit' => 'required|alpha',
		'Quantity' => 'required|numeric',

	);

	public static $updateRules = array(
		'Item Type' => 'min:5|max:100',
		'Brand' => 'min:2|max:100',
		'Unit' => 'alpha',
		'Quantity' => 'numeric'
	);

	public function itemtype()
	{
		return $this->hasOne('App\ItemType','id','itemtype_id');
	}

	public function scopeBrand($query,$brand)
	{
		return $query->where('brand','=',$brand);
	}

	public function scopeItemType($query,$name)
	{
		$itemtype = ItemType::where('name','=',$name)->first();
		return $query->where('itemtype_id','=',$itemtype->id);
	}

	/*
	*
	*	@supply accepts supply id
	*	@quantity amount to add to the supply
	* 	Note: Validate before using this method
	*	Create a accept method
	*	
	*/
	public static function add($supply,$quantity)
	{

		$supply = Supply::find($supply);
		$supply->quantity = $supply->quantity + $quantity;
		$supply->save();
	}


	/*
	*
	*	@supply accepts supply id
	* 	Note: Validate before using this method
	*	Create a release for workstation method
	*	
	*/
	public static function releaseForWorkstation($brand)
	{
		DB::transaction(function() use ($brand) {
			/*
			*
			*	@supply accepts supply id
			*	Validate supply id before sending to this function
			*	Reduce quantity of supply
			*
			*/
			$supply = Supply::brand($brand)->first();
			$supply->quantity = $supply->quantity - 1;
			$supply->save();

			/*
			*
			*	Create a record on supply history table
			*	
			*
			*/
			SupplyHistory::assignToWorkstationRecord($supply->id);
		});
	}


	/*
	*
	*	@id accepts supply id
	* 	Note: Validate before using this method
	*	Create a release method
	*	
	*/
	public static function release($id,$quantity,$purpose,$name)
	{
		DB::transaction(function() use($id,$quantity,$purpose,$name)  
		{
			/*
			*
			*	@supply accepts supply id
			*	Validate supply id before sending to this function
			*	Reduce quantity of supply
			*
			*/
			$supply = Supply::find($id);
			$supply->quantity = $supply->quantity - 1;
			$supply->save();

			/*
			*
			*	Create a record on supply history table
			*	
			*
			*/
			SupplyHistory::createRecord($supply->id,$quantity,$purpose,$name);
		});
	}
}
