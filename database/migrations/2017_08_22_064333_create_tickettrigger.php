<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTickettrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         DB::unprepared("
            CREATE TRIGGER `ticket_trg` 
            AFTER INSERT ON `ticket`
            FOR EACH ROW BEGIN 

                SET @item_ticket = (
                    SELECT ticket_id 
                    FROM item_ticket 
                    WHERE ticket_id = new.ticket_id
                );

                SET @pc_ticket = (
                    SELECT ticket_id 
                    FROM pc_ticket 
                    WHERE ticket_id = new.ticket_id
                );

                SET @room_ticket = (
                    SELECT ticket_id 
                    FROM room_ticket 
                    WHERE ticket_id = new.ticket_id
                );

                SET @item_id = (
                    SELECT item_id 
                    FROM item_ticket 
                    WHERE ticket_id = new.ticket_id
                );
                SET @pc_id = (
                    SELECT pc_id 
                    FROM pc_ticket 
                    WHERE ticket_id = new.ticket_id
                );
                SET @room_id = (
                    SELECT room_id 
                    FROM room_ticket 
                    WHERE ticket_id = new.ticket_id
                );

                IF @item_ticket IS NOT NULL THEN
                    INSERT INTO item_ticket(item_id,ticket_id) 
                    VALUES (@item_id,new.id);
                ELSEIF @pc_ticket IS NOT NULL THEN
                    INSERT INTO pc_ticket(pc_id,ticket_id) 
                    VALUES (@pc_id,new.id);
                ELSEIF @room_ticket IS NOT NULL THEN
                    INSERT INTO room_ticket(room_id,ticket_id) 
                    VALUES (@room_id,new.id);
                END IF;
                
            END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
