<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'unit';
	public $timestamps = true;

	public $fillable = ['unit'];
	protected $primaryKey = 'id';

	public static $rules = array(
		
		'Inventory ID' => 'required|exists:inventory,id',
		'Unit' => 'required'
	);
	public static $updaterules = array(
		'Inventory ID' => ''
		'Unit' => ''
	);

}
