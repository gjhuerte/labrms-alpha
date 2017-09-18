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

		Purpose::insert(array(
		[
      	 'title'=>'Oral Defense',
      	 'description'=>''
		],
		[
      	 'title'=>'General Assembly',
      	 'description'=>''
		],
		[
      	 'title'=>'Seminar',
      	 'description'=>''
		],
		[
      	 'title'=>'Tutorial',
      	 'description'=>''
		],
		[
      	 'title'=>'Make-up Classes',
      	 'description'=>''
		],
		[
      	 'title'=>'Class Presentation',
      	 'description'=>''
		],
		[
      	 'title'=>'Class Activity',
      	 'description'=>''
		]
		));
	}

}
