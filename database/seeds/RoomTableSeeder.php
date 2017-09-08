<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Room;

class RoomTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
   	//delete users table records
   	DB::table('room')->delete();
   	//insert some dummy records
   	Room::insert(array(
       [
        'name' => 'S501',
        'category' => 'Web Development'
        /*'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')*/
       ],
       [
        'name' => 'S502',
        'category' => 'Networking'
        /*'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')*/
       ],
       [
        'name' => 'S503',
        'category' => 'Networking'
        /*'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')*/
       ],
       [
        'name' => 'S504',
        'category' => 'Hardware,Networking'
        /*'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')*/
       ],
       [
        'name' => 'Consultation Room',
        'category' => 'Consultation,Meeting'
        /*'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')*/
       ],
       [
        'name' => 'Faculty Room',
        'category' => 'Faculty Area'
        /*'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')*/
       ],
       [
        'name' => 'Server',
        'category' => ''
        /*'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')*/
       ],
       [
        'name' => 'S508',
        'category' => 'Programming,Web Development'
        /*'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')*/
       ],
       [
        'name' => 'S510',
        'category' => 'Database,Web Development,Multimedia'
        /*'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')*/
       ],
       [
        'name' => 'S511',
        'category' => 'Multimedia'
        /*'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')*/
       ]
    ));

	}


}
