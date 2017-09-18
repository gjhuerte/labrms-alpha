<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends \Eloquent
{

	protected $table = 'semester';
	protected $primaryKey = 'id';
	public $timestamps = true;
	public $fillable = ['semester','datestart','dateend'];
	protected $dates = ['datestart','dateend'];

	public static $rules = array(
		'Semester' => 'required|min:2|max:100',
		'Semester Start' => 'required|date',
		'Semester End' => 'required|date'
	);
	public static $updateRules = array(
		'Semester' => 'required|min:2|max:100',
		'Semester Start' => 'required|date',
		'Semester End' => 'required|date'
	);
}
