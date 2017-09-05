<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Tickettype;

class TickettypeTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
	   	//delete users table records
	   	DB::table('tickettype')->delete();

	    Tickettype::insert(array(
	    	['type'=>'Complaint'],
			['type'=>'Action Taken'],
			['type'=>'Transfer'],
			['type'=>'Maintenance'],
			['type'=>'Lent'],
			['type'=>'Incident']));
	}

}
