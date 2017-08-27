<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomCategory extends Model
{
    protected $table = 'roomcategory';
	
	public $timestamps = false;

	public $fillable = ['category'];
	public $incrementing = false;
	protected $primaryKey = 'category';
	public static $rules = array(
		'Category' => 'required|string|min:5|max:100'
	);

	public static $updateRules = array(
		'Category' => 'string|min:5|max:100'
	);
}