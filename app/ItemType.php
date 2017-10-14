<?php

namespace App;

use DB;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ItemType extends \Eloquent{
	// use SoftDeletes;

	/**
	*
	* table name
	*
	*/	
	protected $table = 'itemtype';

	/**
	*
	* primary key
	*
	*/
	protected $primaryKey = 'id';

	/**
	*
	*	fields to be set as date
	*
	*/
	// protected $dates = ['deleted_at'];

	/**
	*
	* created_at and updated_at status
	*
	*/
	public $timestamps = true;

	/**
	*
	* used for create method
	*
	*/  
	public $fillable = [
		'name',
		'description',
		'category'
	];

	/**
	*
	* validation rules
	*
	*/
	public static $rules = array(
		'name' => 'required|min:2|max:50|unique:itemtype,name',
		'description' => 'required|min:5|max:450'
	);

	/**
	*
	* update rules
	*
	*/
	public static $updateRules = array(
		'name' => 'min:2|max:50',
		'description' => 'min:5|max:450'
	);

	public function itemprofile()
	{
		return $this->hasManyThrough('App\ItemProfile','App\Inventory','id','id');
	}

	/**
	*
	*	@param $type accepts the type name
	*	usage: ItemType::type('System Unit')->get();
	*
	*/
    public function scopeType($query,$type)
    {
    	return $query->where('name','=',$type);
    }

    /**
    *
    *	saves record to database
    *	@param $name
    *	@param $description
    *	@param $category
    *	@return item type details
    *
    */
    public static function createRecord($name,$description,$category)
    {
    	DB::transaction(function() use ($name,$description,$category)
    	{
			$itemtype = new ItemType;
			$itemtype->name = $name;
			$itemtype->description = $description;
			$itemtype->category = $category;
			$itemtype->save();
			return $itemtype;
    	});
    }
}
