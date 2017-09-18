<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $table = 'academic_years';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public static $rules = [
        'Date Started' => 'required|date',
        'Date Ended' => 'required|date'
    ];
}
