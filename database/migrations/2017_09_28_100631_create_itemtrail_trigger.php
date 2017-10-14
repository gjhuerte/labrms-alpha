<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemtrailTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('

            CREATE TRIGGER `itemtrail_trg` BEFORE 
            UPDATE ON `itemprofile`
            FOR EACH ROW 
                INSERT INTO itemtrail( propertynumber,
                                       oldlocation,
                                       newlocation,
                                       timetransfered) 
                VALUES(old.propertynumber,old.location,new.location,new.UPDATED_AT)
                ;
            ');
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
