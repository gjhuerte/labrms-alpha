<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends \Eloquent{
	//Database driver
	/*
		1 - Eloquent (MVC Driven)
		2 - DB (Directly query to SQL database, no model required)
	*/

	//The table in the database used by the model.
	protected $table = 'reservation';
	public $timestamps = true;
	public $fillable = [
		'item_id',
		'purpose_id',
		'user_id',
		'faculty-in-charge',
		'location',
		'dateofuse',
		'timein',
		'timeout',
		'approval'
	];
	protected $primaryKey = 'id';
	//Validation rules!
	public static $rules = array(
		'Location' => 'required|between:4,100',
		'Time started' => 'required|date',
		'Time end' => 'required|date',
		'Purpose' => 'required',
		'Faculty-in-charge' => 'required|between:5,50'
	);

	public static $updateRules = array(
		'Location' => 'required|between:4,100',
		'Time started' => 'required|date',
		'Time end' => 'required|date',
		'Purpose' => 'required',
		'Faculty-in-charge' => 'required|between:5,50'
	);

	public function user()
	{
			return $this->belongsTo('App\User','user_id','id');
	}

	public function scopeApproved($query)
	{
		return $this->where('approval','=',1);
	}

	public function scopeDisapproved($query)
	{
		return $this->where('approval','=',2);
	}

	public function scopeUndecided($query)
	{
		return $this->where('approval','=',0);
	}

	/**
	*
	*	check if there is existing reservation
	*	@param start time of reservation
	*	@param end time of reservation
	*	@return false if not
	*	@return reservation info
	*
	*/
	public static function hasReserved($start,$end)
	{
		$reservations = Reservation::whereBetween('timein',[$start->startOfDay(),$start->endOfDay()])
							->approved()
							->get();
		foreach($reservations as $reservation)
		{
			$dateofuse = Carbon::parse($reservation->time_start);
			if(Carbon::parse($start)->isSameDay($dateofuse))
			{
				$timein = Carbon::parse($reservation->timein);
				$timeout = Carbon::parse($reservation->timeout);

				if( Carbon::parse($start)->between( $timein , $timeout ) )
				{
					return $reservation;
				}

				if( Carbon::parse($end)->between( $timein , $timeout ) )
				{
					return $reservation;
				}
			}
		}

		return false;
	}
}
