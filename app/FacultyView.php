<?php

namespace App;

use App\RoomSchedule;
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

	public function roomschedule()
	{
		return $this->hasMany('App\RoomSchedule','faculty','id');
	}
	
}
