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
		'Middle Name' => '',
		'Last Name' => 'required',
		'Course Year Section' => 'required',
		'Time In' => 'required',
		'Time Out' => '',
		'Item' => 'required|exists:itemprofile,id',
		
	); 

	/**
	*
	* update rules
	*
	*/
	public static $updateRules = array(
		'First Name' => '',
		'Middle Name' => '',
		'Last Name' => '',
		'Course Year Section' => '',
		'Time In' => '',
		'Time Out' => 'required',
		'Item' => 'exists:itemprofile,id',
	);

	public function itemprofile()
	{
		return $this->belongsTo('App\ItemProfile','item_id','id');
	}

}
