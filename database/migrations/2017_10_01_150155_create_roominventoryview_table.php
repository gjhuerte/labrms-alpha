<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoominventoryviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW IF NOT EXISTS roominventory_v
            AS 

            SELECT 
            itemprofile.propertynumber as `item`,
            room.name as `room`,
            `item_v`.itemtype as `type`
            FROM roominventory 
            inner join itemprofile 
                on roominventory.item_id = itemprofile.id
            left join room
                on roominventory.room_id = room.id
            left join item_v
                on itemprofile.propertynumber = item_v.propertynumber
            where itemprofile.id not in (
                    select systemunit_id as id 
                    from pc
                    union distinct
                    select monitor_id as id 
                    from pc
                    union distinct
                    select keyboard_id as id 
                    from pc
                    union distinct
                    select avr_id as id 
                    from pc
            )

            UNION DISTINCT

            SELECT 
            pc.name as `item`,
            room.name as `room`,
            'Workstation' as `type`
            FROM roominventory 
            inner join itemprofile 
                on roominventory.item_id = itemprofile.id
            left join room
                on roominventory.room_id = room.id
            left join pc
                on (itemprofile.id = pc.systemunit_id or 
                    itemprofile.id = pc.keyboard_id or 
                    itemprofile.id = pc.monitor_id or 
                    itemprofile.id = avr_id)
            where itemprofile.id in (
                    select systemunit_id as id 
                    from pc
                    union distinct
                    select monitor_id as id 
                    from pc
                    union distinct
                    select keyboard_id as id 
                    from pc
                    union distinct
                    select avr_id as id 
                    from pc
            )

        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS roominventory_v;");
    }
}
