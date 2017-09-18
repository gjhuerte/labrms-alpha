<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomReservationviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up() 
    {
        DB::statement("
            CREATE VIEW IF NOT EXISTS roomreservation_v 
            AS 
            SELECT 
                user.lastname,
                user.firstname,
                room.name,
                reservation.timein,
                reservation.timeout,
                reservation.purpose,
                reservation.location,
                reservation.approval,
                reservation.facultyincharge,
                reservation.remark
            FROM roomreservation
            JOIN reservation
                ON roomreservation.reservation_id = reservation.id 
            JOIN room 
                ON room.id = roomreservation.room_id
            JOIN user
                ON reservation.user_id = user.id;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW roomreservation_v;");
    }

}
