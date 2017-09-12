<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomReservationView extends Model
{
	//The table in the database used by the model.
	protected $table  = 'roomreservation_v';
	public $timestamps = false;
}
