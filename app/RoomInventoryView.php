<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomInventoryView extends Model
{
	//The table in the database used by the model.
	protected $table  = 'roominventory_v';
	public $timestamps = false;
}
