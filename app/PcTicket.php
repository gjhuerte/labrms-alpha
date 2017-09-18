<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PcTicket extends \Eloquent{
	protected $table = 'pc_ticket';
	protected $primaryKey = 'id';

	public $timestamps = true;
	public $fillable = ['pc_id','ticket_id'];

	public function scopeTicket($query,$value)
	{
		return $query->where('ticket_id','=',$value);
	}

	// public function ticket()
	// {
	// 	return $this->hasMany('App\Ticket','ticket_id','ticket_id');
	// }
}