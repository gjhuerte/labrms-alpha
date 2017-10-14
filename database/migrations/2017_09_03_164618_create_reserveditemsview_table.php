<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReserveditemsviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW IF NOT EXISTS reserveditems_v
            AS 
            SELECT 
                user.lastname AS lastname,
                user.middlename AS middlename,
                user.firstname AS firstname,
                itemtype.name AS itemtype,
                inventory.brand AS brand,
                inventory.model AS model,
                itemprofile.propertynumber AS propertynumber,
                reservation.timein AS timein,
                reservation.timeout AS timeout,
                reservation.purpose AS purpose,
                reservation.created_at AS created_at,
                reservation.location AS location,
                reservation.approval AS approval,
                reservation.facultyincharge AS facultyincharge,
                reservation.remark AS remark 
            FROM item_reservation
            JOIN reservation
                on item_reservation.reservation_id = reservation.id
            JOIN itemprofile 
                on itemprofile.id = item_reservation.item_id
            JOIN inventory
                on inventory.id = itemprofile.inventory_id
            JOIN user
                on reservation.user_id = user.id
            JOIN itemtype
                on inventory.itemtype_id = itemtype.id
            ;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS reserveditems_v;");
    }
}
