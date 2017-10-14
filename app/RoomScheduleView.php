<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RoomScheduleView extends Model
{
    protected $table = 'roomschedule_v';

    public function scopeCurrent($query)
    {
		    
		/*
		|--------------------------------------------------------------------------
		|
		| 	set values
		|
		|--------------------------------------------------------------------------
		|
		*/
		$date = Carbon::now()->format('Y-m-d H:i');
		    
		/*
		|--------------------------------------------------------------------------
		|	
		| 	return schedule current in effect
		|
		|--------------------------------------------------------------------------
		|
		*/
		$query->whereRaw('? between semesterstart and semesterend',[ $date ]);
    }
}
