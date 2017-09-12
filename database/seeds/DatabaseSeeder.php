<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		$this->call(UserTableSeeder::class);
		$this->call(RoomTableSeeder::class);
		$this->call(ItemtypeTableSeeder::class);
		$this->call(TickettypeTableSeeder::class);
		$this->call(PurposeTableSeeder::class);
		$this->call(SoftwareTypeTableSeeder::class);
		$this->call(RoomCategoryTableSeeder::class);
		$this->call(LanguageTableSeeder::class);
		$this->call(SettingsTableSeeder::class);

	}

}
