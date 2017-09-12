<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemView extends Model
{

	/**
	*
	* table name
	*
	*/	
    protected $table = 'item_v';

	/**
	*
	* created_at and updated_at status
	*
	*/
	public $timestamps = false; 
}
