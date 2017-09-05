<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ItemReservation extends \Eloquent{
	use  SoftDeletes;

	/**
	*
	* table name
	*
	*/	
	protected $table  = 'item_reservation';

	/**
	*
	*	fields to be set as date
	*
	*/
	protected $dates = ['deleted_at'];

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
	public $timestamps = false;

}