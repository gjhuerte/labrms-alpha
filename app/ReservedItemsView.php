<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReservedItemsView extends Model
{
	//The table in the database used by the model.
	protected $table  = 'reserveditems_v';
	protected $dates = [
		'deleted_at',
		'timein',
		'timeout'
	];
	//The attribute that used as primary key.

	public $timestamps = false;

	public function scopeApproved($query)
	{
		return $query->where('approval','=',1);
	}

	public function scopeDisapproved($query)
	{
		return $query->where('approval','=',2);
	}

	public function scopeReserved($query,$date,$timein,$timeout)
	{
		    
		/*
		|--------------------------------------------------------------------------
		|
		| 	set values
		|
		|--------------------------------------------------------------------------
		|
		*/
		$start = Carbon::parse($date . " " . $timein, 'F d Y h:m A' )->format('Y-m-d H:i');
		$end = Carbon::parse($date . " " . $timeout, 'F d Y h:m A' )->format('Y-m-d H:i');
		    
		/*
		|--------------------------------------------------------------------------
		|	
		| 	return reservation between the date stated
		|
		|--------------------------------------------------------------------------
		|
		*/
		return $query->whereBetween( 'timein' , [ $start , $end ] )
					->orWhereBetween( 'timeout' , [ $start , $end ] )
					->orWhereRaw('? between timein and timeout',[ $start ])
					->orWhereRaw('? between timein and timeout',[ $end ])
					->approved();
	}

	public function scopeItemType($query,$itemtype)
	{
		return $query->where('itemtype','=',$itemtype);
	}
}
