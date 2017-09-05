<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacultyView extends Model
{
	
	/**
	*
	*	table name
	*
	*/
    protected $table  = 'facultyview';

	/**
	*
	*	The attribute that used as primary key.
	*
	*/
	// protected $primaryKey = 'id';

	/**
	*
	*	created_at and updated_at status
	*
	*/
	public $timestamps = false;
	
}
