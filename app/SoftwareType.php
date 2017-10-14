<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoftwareType extends \Eloquent
{
    protected $table = 'softwaretype';
	
	public $timestamps = false;

	public $fillable = ['type'];
	public $incrementing = false;
	protected $primaryKey = 'type';
	public static $rules = array(
		'Type' => 'required|string|min:5|max:100'
	);

	public static $updateRules = array(
		'Type' => 'string|min:5|max:100'
	);
}
