<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Purpose;

class PurposeTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//delete purpose table records
		DB::table('purpose')->delete();

		Purpose::create(array(
      'title'=>'Oral Defense',
      'description'=>''
		),array(
      'title'=>'General Assembly',
      'description'=>''
		),array(
      'title'=>'Seminar',
      'description'=>''
		),array(
      'title'=>'Tutorial',
      'description'=>''
		),array(
      'title'=>'Make-up Classes',
      'description'=>''
		),array(
      'title'=>'Class Presentation',
      'description'=>''
		),array(
      'title'=>'Class Activity',
      'description'=>''
		));
	}



}
