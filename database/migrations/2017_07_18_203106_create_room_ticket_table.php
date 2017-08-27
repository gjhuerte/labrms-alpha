<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomTicketTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('room_ticket', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('room_id')->unsigned();
			$table->foreign('room_id')->references('id')->on('room')
									->onUpdate('cascade')
									->onDelete('cascade');
            $table->integer('ticket_id')->unsigned();
			$table->foreign('ticket_id')->references('id')->on('ticket')
									->onUpdate('cascade')
									->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('room_ticket');
	}

}
