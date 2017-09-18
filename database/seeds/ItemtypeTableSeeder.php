<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Itemtype;

class ItemtypeTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//delete users table records
		DB::table('itemtype')->delete();
		//insert some dummy records
		Itemtype::insert(array(
		[
		   'name' => 'System Unit',
		   'description' => 'Computer set',
		   'category' => 'equipment'
		],
		[
		 'name' => 'Display',
		 'description' => 'Visual aids',
		 'category' => 'equipment'
		],
		[
		 'name' => 'AVR',
		 'description' => 'Power Regulator',
		 'category' => 'equipment'
		],
		[
		 'name' => 'Aircon',
		 'description' => 'Cooling appliance',
		 'category' => 'equipment'
		],
		[
 		 'name' => 'TV',
		 'description' => 'Visual aids',
		 'category' => 'equipment'
		],
		[
		 'name' => 'Projector',
		 'description' => 'Visual aids',
		 'category' => 'equipment'
		],
		[
		 'name' => 'Extension',
		 'description' => 'Extension cord or any other power source',
		 'category' => 'supply'
		],
		[
		 'name' => 'Keyboard',
		 'description' => 'Computer parts used as an input',
		 'category' => 'equipment'
		],
		[
		 'name' => 'Mouse',
		 'description' => '',
		 'category' => 'supply'
		],

		));
		
	}
}
