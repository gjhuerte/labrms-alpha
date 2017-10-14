<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LostAndFoundItems extends Model
{
    protected $table = 'lost_and_found_items';
    protected $primaryKey = 'id';

    public $fillable = [

    ];

    public static $rules = [
    	'Identifier' => 'required|unique:lost_and_found_items,identifier',
    	'Description' => 'required',
    	'Date Found' => 'required'
    ];

    public static $updateRules = [
        'Identifier' => 'required',
        'Description' => 'required',
        'Date Found' => 'required'
    ];

    public static $claimRules = [
    	'ID' => 'required|exists:lost_and_found_items,id',
    	'Claimant' => 'required'
    ];
}
