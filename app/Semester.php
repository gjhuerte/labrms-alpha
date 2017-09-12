<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{

	protected $table = 'systemtime';
	public $timestamps = true;
	protected $primaryKey = 'id';
	// public $fillable = ['softwarename','softwaretype','licensetype','company','minsysreq','maxsysreq'];

	// public static $rules = array(
	// 	'Software Name' => 'required|min: 2|max: 100',
	// 	'Software Type' => 'required|min: 2|max: 100',
	// 	'License Type' => 'required|min: 2|max: 100',
	// 	'Company' => 'min: 2|max: 100',
	// 	'Minimum System Requirement' => 'min: 2|max: 100',
	// 	'Recommended System Requirement' => 'min: 2|max: 100'

	// );

	// public static $updateRules = array(
	// 	'Software Name' => 'alpha|min: 2|max: 100',
	// 	'Software Type' => 'alpha|min: 2|max: 100',
	// 	'License Type' => 'alpha|min: 2|max: 100',
	// 	'Company' => 'alpha|min: 2|max: 100',
	// 	'Minimum System Requirement' => 'alpha|min: 2|max: 100',
	// 	'Recommended System Requirement' => 'alpha|min: 2|max: 100'
	// );

	// public static $types = [
	// 		'Programming',
	// 		'Database',
	// 		'Multimedia',
	// 		'Networking'
	// ];
}
