<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ReservationItems extends \Eloquent{

	/**
	*
	* table name
	*
	*/	
	protected $table = 'reservationitems';

	/**
	*
	* primary key
	*
	*/
	protected $primaryKey = 'id';

	/**
	*
	* created_at and updated_at status
	*
	*/
	public $timestamps = false;

	/**
	*
	* used for create method
	*
	*/  
	public $fillable = [
		'itemtype_id',
		'inventory_id',
		'included',
		'excluded'
	];

	/**
	*
	* validation rules
	*
	*/
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

	public function scopeEnabled($query)
	{
		return $query->where('status','=','Enabled');
	}

	public function scopeDisabled($query)
	{
		return $query->where('status','=','Disabled');
	}
}
