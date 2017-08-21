<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ReservationItems extends \Eloquent{
	//Database driver
	/*
		1 - Eloquent (MVC Driven)
		2 - DB (Directly query to SQL database, no model required)
	*/

	//The table in the database used by the model.
	protected $table = 'reservationitems';

	//The attribute that used as primary key.
	protected $primaryKey = 'id';
	public $timestamps = false;
	public $fillable = ['itemtype_id','inventory_id','included','excluded'];

	public static $rules = [
		'itemtype' => 'required|exists:itemtype,id',
		'inventory' => 'required|exists:inventory,id'
	];

	public function itemtype()
	{
		return $this->belongsTo('App\ItemType','itemtype_id','id');
	}

	public function inventory()
	{
		return $this->belongsTo('App\Inventory','inventory_id','id');
	}
}
