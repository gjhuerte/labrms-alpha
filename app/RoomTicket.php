<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class RoomTicket extends \Eloquent{
	protected $table = 'room_ticket';
	protected $primaryKey = 'id';

	public $timestamps = true;
	public $fillable = ['room_id','ticket_id'];

	public function ticket()
	{
		return $this->belongsTo('App\Ticket','ticket_id','id');
	}

	public function room()
	{
		return $this->belongsTo('Room','room_id','id');
	}

	public function scopeTicket($query,$value)
	{
		$query->where('ticket_id','=',$value);
	}

}