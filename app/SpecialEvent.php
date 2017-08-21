<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SpecialEvent extends \Eloquent{
  protected $table = 'event';
  protected $primaryKey = 'id';

  public $timestamps = true;

  public $fillable = ['title','date','repeating','repeatingFormat'];

  public static $rules = [
  	'title' => 'required',
  	'date' => 'required|date',
  ];

public static $updateRules = [
  	'title' => '',
  	'date' => ''
  ];
}
