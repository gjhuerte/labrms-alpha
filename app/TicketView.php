<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Auth;

class TicketView extends \Eloquent
{

	/**
	*
	* table name
	*
	*/	
	protected $table = 'ticket_v';

	/**
	*
	*	fields to be set as date
	*
	*/
	protected $dates = ['date'];

	/**
	*
	* created_at and updated_at status
	*
	*/
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

	public function scopeOpen($query)
	{
		return $query->where('status','=','Open');
	}

	public function scopeClosed($query)
	{
		return $query->where('status','=','Closed');
	}

	public function scopeSelf($query)
	{
		$name = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
		return $query->where( 'author' , '=' , $name );
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
