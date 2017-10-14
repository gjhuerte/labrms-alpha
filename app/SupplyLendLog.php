<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplyLendLog extends Model
{

	/**
	*
	* table name
	*
	*/	
    protected $table = 'supplylendlog';

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
		'supply_id'
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
		'Supply' => 'required|exists:supply,brand',
		'Type' => 'required|exists:itemtype,name',
		
	); 

	/**
	*
	* item rule
	*
	*/
	public static $itemRule = array(
		'Supply' => 'required|array',
		
	); 

	/**
	*
	* update rules
	*
	*/
	public static $updateRules = array(
		'Supply' => 'exists:supply,id',
	);

	public function supply()
	{
		return $this->belongsTo('App\Supply','supply_id','id');
	}

}
