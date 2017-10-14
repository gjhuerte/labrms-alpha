<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ItemLog extends \Eloquent
{

	/**
	*
	* table name
	*
	*/	
	protected $table = 'itemlog';

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
		'log_id',
		'item_id',
		'facultyincharge',
		'remark'
	];

	/**
	*
	* The attribute that used as primary key.
	*
	*/
	protected $primaryKey = 'id';  

	/**
	*
	* validation rules
	*
	*/
	public static $rules = array(

		'Log id' => 'required|exists:log,id',
		'Item Id' => 'required|exists:itemprofile,id',
		'Faculty In Charge' => 'alpha|min:5|max:100',
		'Remark' => 'alpha|min:5|max:200'
		
	);

	/**
	*
	* update rules
	*
	*/
	public static $updateRules = array(
		'Log id' => 'required|exists:log,id',
		'Item Id' => 'required|exists:itemprofile,id',
		'Faculty In Charge' => 'alpha|min:5|max:100',
		'Remark' => 'alpha|min:5|max:200'
	);


}