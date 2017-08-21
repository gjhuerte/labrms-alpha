<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TicketView extends \Eloquent{
	//Database driver
	/*
		1 - Eloquent (MVC Driven)
		2 - DB (Directly query to SQL database, no model required)
	*/

	//The table in the database used by the model.
	protected $table = 'ticketview';
	public $timestamps = false;

	public function scopeTickettype($query,$value)
	{
		return $query->where('tickettype','=',$value);
	}

	public function scopeStatus($query,$value)
	{
		return $query->where('status','=',$value);
	}

	public function scopeStaffassigned($query,$value)
	{
		return $query->where('staffassigned','=',$value);
	}

	public function scopeStaff($query,$value = null)
	{
		if(is_null($value))
		{
			return $query;
		}
		else
		{
			return $query->where('staff_id','=',$value);
		}
	}

}
