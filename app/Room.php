<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Room extends \Eloquent{
	//Database driver
	/*
		1 - Eloquent (MVC Driven)
		2 - DB (Directly query to SQL database, no model required)
	*/

 	// use SoftDeletes;
	//The table in the database used by the model.
	protected $table = 'room';
	// protected $dates = ['deleted_at'];

	public $fillable = ['name','category','description','status'];
	public $timestamps = false;
	protected $primaryKey = 'id';
	//Validation rules!
	public static $rules = array(
		'Name' => 'required|min:4|max:100|unique:room,name',
		'Category' => 'required|min:4|max:100',
		'Description' => 'required|min:4'
	);

	public static $updateRules = array(
		'Name' => 'min:4|max:100',
		'Category' => 'min:4|max:100',
		'Description' => 'min:4'

	);

	public function roominventory()
	{
		return $this->hasMany('App\RoomInventory','room_id','id');
	}

	public function scopeLocation($query,$location)
	{
		return $query->where('name','=',$location);
	}

	public function ticket()
	{
		return $this->belongsToMany('App\Ticket','room_ticket','room_id','ticket_id');
	}

	/*
	*
	*	Foreign key referencing ticket table
	*
	*/
	public function roomticket()
	{
		return $this->hasMany('App\RoomTicket','room_id','id');
	}

}
