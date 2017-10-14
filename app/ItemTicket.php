<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ItemTicket extends \Eloquent
{

	/**
	*
	* table name
	*
	*/	
	protected $table = 'item_ticket';

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
	public $timestamps = true;

	/**
	*
	* used for create method
	*
	*/  
	public $fillable = [
		'item_id',
		'ticket_id'
	];

	public function ticket()
	{
		return $this->belongsTo('App\Ticket','ticket_id','id');
	}

	public function scopeTicketID($query,$value)
	{
		return $query->where('ticket_id','=',$value);
	}

	public function itemprofile()
	{
		return $this->belongsTo('App\ItemProfile','item_id','id');
	}

}