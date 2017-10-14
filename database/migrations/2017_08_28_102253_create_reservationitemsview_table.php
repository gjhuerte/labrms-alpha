<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationitemsviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up() 
    {
        DB::statement("
            CREATE VIEW IF NOT EXISTS reservationitems_v AS 
            SELECT
                itemtype.name, 
                inventory.model,
                inventory.brand,
                itemprofile.id,
                itemprofile.propertynumber,
                reservationitems.status
            FROM inventory
            JOIN itemtype
                ON inventory.itemtype_id = itemtype.id
            JOIN reservationitems
                ON reservationitems.inventory_id = inventory.id 
                and reservationitems.itemtype_id = itemtype.id
            JOIN itemprofile
                ON itemprofile.inventory_id = inventory.id
            WHERE reservationitems.status IN ('E','e','Enabled','enabled','Enable','enable') 
            /*  AND (itemprofile.propertynumber IN (reservationitems.included) 
                OR itemprofile.propertynumber NOT IN (reservationitems.excluded) 
                OR reservationitems.included IS NULL 
                OR reservationitems.included IN ('',' ') )  */
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
        DB::statement("DROP VIEW IF EXISTS reservationitems_v;");
    }
}
