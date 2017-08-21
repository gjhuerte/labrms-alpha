<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Purpose extends \Eloquent{

  protected $table = 'purpose';

	public $timestamps = true;

	protected $fillable = ['title','description'];

  public static $rules = [
    'title' => 'required|max:50',
    'description' => 'required'
  ];

  public static $updateRules = [
    'title' => '',
    'description' => ''
  ];

}
