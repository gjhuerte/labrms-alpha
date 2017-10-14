<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\SpecialEvent;
use Auth;

class Reservation extends \Eloquent{
	//Database driver
	/*
		1 - Eloquent (MVC Driven)
		2 - DB (Directly query to SQL database, no model required)
	*/

	//The table in the database used by the model.
	protected $table = 'reservation';
	protected $dates = [
		'timein',
		'timeout'
	];
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

	/**
	*
	*	reservation rules
	*	
	*/
	public static $rules = array(
		'Items' => 'required',
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

	public function itemprofile()
	{
		return $this->belongsToMany('App\ItemProfile','item_reservation','reservation_id','item_id'); 
	}

<<<<<<< HEAD
	public function scopeUnclaimed($query)
	{
		return $query->where(function($query){
			$query->whereNull('status')
					->orWhere('status','=','unclaimed');
		});
	}

=======
>>>>>>> origin/0.3
	public function scopeApproved($query)
	{
		return $query->where('approval','=',1);
	}

	public function scopeDisapproved($query)
	{
		return $query->where('approval','=',2);
	}

	public function scopeUndecided($query)
	{
		return $query->where('approval','=',0);
	}

	public function scopeWithInfo($query)
	{
		$query = $query->with('itemprofile.inventory.itemtype')
					->with('user');
		if( Auth::user()->accesslevel == 3 || Auth::user()->accesslevel == 4 )
		{
			$query = $query->where('user_id','=',Auth::user()->id);
		}
<<<<<<< HEAD

		return $query;
					
	}

=======

		return $query;
					
	}

>>>>>>> origin/0.3
	public function scopeUser($query,$id)
	{
		return $query->where('user_id','=',$id);
	}

	

	/**
	*	change reservation status to approved
	*	@param reservation id
	*	@return reservation information
	*/
	public static function approve($id)
	{
		$reservation = Reservation::find($id);
		$reservation->approval =  1;
		$reservation->save();

		return $reservation;
	}

	/**
	*	change reservation status to disapproved
	*	@param reservation id
	*	@param reservation reason
	*	@return reservation information
	*/
	public static function disapprove($id,$reason)
	{
		$reservation = Reservation::find($id);
		$reservation->approval = 2;
		$reservation->remark = $reason;
		$reservation->save();
		
		return $reservation;
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

				/*
				|--------------------------------------------------------------------------
				|
				| 	current starting time of reservation is between existing reservation
				|
				|--------------------------------------------------------------------------
				|
				*/
				if( Carbon::parse($start)->between( $timein , $timeout ) )
<<<<<<< HEAD
				{
					return $reservation;
				}

				/*
				|--------------------------------------------------------------------------
				|
				| 	current ending time of reservation is between existing reservation
				|
				|--------------------------------------------------------------------------
				|
				*/
				if( Carbon::parse($end)->between( $timein , $timeout ) )
				{
					return $reservation;
				}

				/*
				|--------------------------------------------------------------------------
				|
				| 	existing reservation start is between current start and end of current reservation
				|
				|--------------------------------------------------------------------------
				|
				*/
				if( Carbon::parse($timein)->between( $start , $end ) )
				{
					return $reservation;
				}

				/*
				|--------------------------------------------------------------------------
				|
				| 	existing reservation end is between current start and end of current reservation 
				|
				|--------------------------------------------------------------------------
				|
				*/
				if( Carbon::parse($timeout)->between( $start , $end ) )
				{
					return $reservation;
				}


			}
		}

		return false;
	}

	/**
	*
	*	set reservation status to 'claimed'
	*	@param id
	*	@return reservation
	*
	*/
	public static function setStatusAsClaimed($id)
	{
		$reservation = "";

		/*
		|--------------------------------------------------------------------------
		|
		| 	find the reservation information
		|
		|--------------------------------------------------------------------------
		|
		*/
		$reservation = Reservation::find($id);

		/*
		|--------------------------------------------------------------------------
		|
		|	if reservation is found
		| 	set status to claimed 
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(count($reservation) > 0)
		{
			$reservation->status = 'Claimed';
			$reservation->save();
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	return reservation information
		|	return null if no reservation found
		|
		|--------------------------------------------------------------------------
		|
		*/
		return $reservation;
=======
				{
					return $reservation;
				}

				/*
				|--------------------------------------------------------------------------
				|
				| 	current ending time of reservation is between existing reservation
				|
				|--------------------------------------------------------------------------
				|
				*/
				if( Carbon::parse($end)->between( $timein , $timeout ) )
				{
					return $reservation;
				}

				/*
				|--------------------------------------------------------------------------
				|
				| 	existing reservation start is between current start and end of current reservation
				|
				|--------------------------------------------------------------------------
				|
				*/
				if( Carbon::parse($timein)->between( $start , $end ) )
				{
					return $reservation;
				}

				/*
				|--------------------------------------------------------------------------
				|
				| 	existing reservation end is between current start and end of current reservation 
				|
				|--------------------------------------------------------------------------
				|
				*/
				if( Carbon::parse($timeout)->between( $start , $end ) )
				{
					return $reservation;
				}


			}
		}

		return false;
>>>>>>> origin/0.3
	}

	/**
	*
	*	find the next reservation date
	*	@param date
	*	@return carbon formatted reservation date
	*
	*/
	public static function thirdWorkingDay($date)
	{

<<<<<<< HEAD
		$curdate = Carbon::parse($date);
		$date = Carbon::parse($date);
=======
		$date = Carbon::parse($date);

>>>>>>> origin/0.3
		$work_days = 0;

		/*
		|--------------------------------------------------------------------------
		|
		| 	this is for checking 
		|	that the date is three days
		|	after the current date
		|
		|--------------------------------------------------------------------------
		|
		*/
		$three_day_rule = 0;
		$date_counter = 0;

		do
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	get the next date
			|
			|--------------------------------------------------------------------------
			|
			*/
<<<<<<< HEAD
			$_date = $date->addDays($date_counter);
=======
			$date = $date->addDays($date_counter);
>>>>>>> origin/0.3
			/*
			|--------------------------------------------------------------------------
			|
			| 	check if date is available for reservation
			|
			|--------------------------------------------------------------------------
			|
			*/
<<<<<<< HEAD
			if(SpecialEvent::isAvailable($_date))
=======
			if(SpecialEvent::isAvailable($date))
>>>>>>> origin/0.3
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	if the date is available
				|	add 1 to working days
				|	add 1 to three day rule
				|
				|--------------------------------------------------------------------------
				|
				*/
				$work_days++;
				$three_day_rule++;
			}

			$date_counter++;

<<<<<<< HEAD
		} while ( $three_day_rule < 3 );

		return $curdate->addDays($work_days);
=======
		} while ( $three_day_rule <= 3 );

		return $date->addDays($work_days);
>>>>>>> origin/0.3
	}
}
