<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Receipt extends \Eloquent{
	
	protected $table  = 'receipt';
	protected $primaryKey = 'id';

	public $timestamps = false;

	protected $fillable = ['number','POno','POdate','invoiceno','invoicedate','fundcode'];

	//Validation rules!
	public static $rules = array(
		'Property Acknowledgement Receipt' => 'required|min:2|max:25',
		'Purchase Order Number' => 'required|min:2|max:25',
		'Purchase Order Date' => 'required|min:2|max:25|date',
		'Invoice Number' => 'required|min:2|max:25',
		'Invoice Date' => 'required|min:2|max:25|date',
		'Fund Code' => 'min:2|max:25'
	);

	public static $updateRules = array(
		'Property Acknowledgement Receipt' => 'min:2|max:25',
		'Purchase Order Number' => 'min:2|max:25',
		'Purchase Order Date' => 'min:2|max:25|date',
		'Invoice Number' => 'min:2|max:25',
		'Invoice Date' => 'min:2|max:25|date',
		'Fund Code' => 'min:2|max:25'
	);


}
