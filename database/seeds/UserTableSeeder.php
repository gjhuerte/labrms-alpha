<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//delete users table records
		DB::table('user')->delete();

		//insert some dummy records
		User::insert([
			[
			   'username' => 'admin',
			   'password' => Hash::make('12345678'),
			   'accesslevel' =>'0',
				 'firstname' => 'Carlo',
				 'middlename' => '',
				 'lastname' => 'Inovero',
				 'contactnumber' => '09123456789',
				 'email' => 'cginovero@gmail.com',
				 'type' => 'faculty',
				 'status' => '1'
			],
			[
			   'username' => 'jheotestor',
			   'password' => Hash::make('12345678'),
			   'accesslevel' =>'1',
				 'firstname' => 'Jheo',
				 'middlename' => '',
				 'lastname' => 'Testor',
				 'contactnumber' => '09123456789',
				 'email' => 'email@email.com',
				 'type' => 'faculty',
				 'status' => '1'
			],
			[
			   'username' => 'kristian',
			   'password' => Hash::make('12345678'),
			   'accesslevel' =>'2',
				 'firstname' => 'Kristian',
				 'middlename' => '',
				 'lastname' => 'Espinosa',
				 'contactnumber' => '09123456789',
				 'email' => 'email@email.com',
				 'type' => 'student',
				 'status' => '1'
			],
			[
			   'username' => 'ajablir',
			   'password' => Hash::make('12345678'),
			   'accesslevel' =>'3',
				 'firstname' => 'Adrian Joseph',
				 'middlename' => '',
				 'lastname' => 'Ablir',
				 'contactnumber' => '09123456789',
				 'email' => 'email@email.com',
				 'type' => 'faculty',
				 'status' => '1'
			],
			[
			   'username' => 'gabriel',
			   'password' => Hash::make('12345678'),
			   'accesslevel' =>'4',
				 'firstname' => 'Gabriel Jay',
				 'middlename' => '',
				 'lastname' => 'Huerte',
				 'contactnumber' => '09123456789',
				 'email' => 'gjhuerte@gmail.com',
				 'type' => 'student',
				 'status' => '1'
			]
		]);
	}



}
