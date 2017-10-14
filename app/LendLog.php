<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LendLog extends Model
{

	/**
	*
	* table name
	*
	*/	
    protected $table = 'lendlog';

	/**
	*
	* primary key
	*
	*/
	protected $primaryKey = 'id';

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
		'firstname',
		'middlename',
		'lastname',
		'courseyearsection',
		'facultyincharge',
		'timein',
		'timeout',
		'item_id'
	];

	/**
	*
	* validation rules
	*
	*/
	public static $rules = array(
		'First Name' => 'required',
		'Last Name' => 'required',
		'Course Year and Section' => 'required',
		'Item' => 'required|exists:itemprofile,propertynumber',
		
	); 

	/**
	*
	* item rule
	*
	*/
	public static $itemRule = array(
		'Item' => 'required|array',
		
	); 

	/**
	*
	* update rules
	*
	*/
	public static $updateRules = array(
		'Item' => 'exists:itemprofile,id',
	);

	public function itemprofile()
	{
		return $this->belongsTo('App\ItemProfile','item_id','id');
	}

}
