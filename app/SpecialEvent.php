<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SpecialEvent extends \Eloquent{
  protected $table = 'event';
  protected $primaryKey = 'id';

  public $timestamps = true;

  public $fillable = ['title','date','repeating','repeatingFormat'];

  protected $dates = [
    'date'
  ];

  public static $rules = [
  	'title' => 'required',
  	'date' => 'required|date',
  ];

  public static $updateRules = [
  	'title' => '',
  	'date' => ''
  ];

  /**
  *
  * check if schedule is available, 
  * return true if availabl
  * return false if not
  * @param $date
  * @return true , false
  *
  */
  public static function isAvailable($date)
  {

    $event = SpecialEvent::all();
    $date = Carbon::parse($date);

    /*
    |--------------------------------------------------------------------------
    |
    |   loop each event
    |
    |--------------------------------------------------------------------------
    |
    */
    foreach($event as $event)
    {

      /*
      |--------------------------------------------------------------------------
      |
      |   if same date as event
      |
      |--------------------------------------------------------------------------
      |
      */
      if(Carbon::parse($event->date)->isSameDay($date))
      {
        return false;
      }

      /*
      |--------------------------------------------------------------------------
      |
      |   if event is repeating
      |
      |--------------------------------------------------------------------------
      |
      */
      if($event->repeating == 1)
      {

        /*
        |--------------------------------------------------------------------------
        |
        |   weekly
        |
        |--------------------------------------------------------------------------
        |
        */
        if($event->repeating == 'weekly')
        {

          /*
          |--------------------------------------------------------------------------
          |
          |   check if date of week of date equals 
          |   date of week of event
          |
          |--------------------------------------------------------------------------
          |
          */
          if( Carbon::parse($event->date)->dayOfWeek == Carbon::parse($date)->dayOfWeek )
          {
            return false;
          }
        }

        /*
        |--------------------------------------------------------------------------
        |
        |   monthly
        |
        |--------------------------------------------------------------------------
        |
        */
        if($event->repeating == 'monthly')
        {

          /*
          |--------------------------------------------------------------------------
          |
          |   check if date of month of date equals 
          |   date of month of event
          |
          |--------------------------------------------------------------------------
          |
          */
          if( Carbon::parse($event->date)->month == Carbon::parse($date)->month )
          {
            return false;
          } 
        }

        /*
        |--------------------------------------------------------------------------
        |
        |   yearly
        |
        |--------------------------------------------------------------------------
        |
        */
        if($event->repeating == 'yearly')
        {

          /*
          |--------------------------------------------------------------------------
          |
          |   check if date of year of date equals 
          |   date of year of event
          |
          |--------------------------------------------------------------------------
          |
          */
          if( Carbon::parse($event->date)->year == Carbon::parse($date)->year )
          {
            return false;
          }
        }

      }

    }

    /*
    |--------------------------------------------------------------------------
    |
    |   return false if not in conditions
    |
    |--------------------------------------------------------------------------
    |
    */
    return true;
  }

}
