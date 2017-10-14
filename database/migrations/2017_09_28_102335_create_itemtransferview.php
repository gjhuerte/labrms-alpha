<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemtransferview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW itemtransfer_v  AS  
            SELECT CONCAT(inventory.brand," ",inventory.model) AS 'equipment',itemprofile.propertynumber,itemtrail.oldlocation,itemtrail.newlocation,itemtrail.timetransfered
            FROM itemtrail          
                JOIN itemprofile 
                    ON itemtrail.propertynumber = itemprofile.propertynumber
                JOIN inventory 
                    ON inventory.id = itemprofile.inventory_id
                JOIN itemtype 
                    ON inventory.itemtype_id = itemtype.id
            WHERE itemtype.category = 'Equipment' OR itemtype.category = 'equipment' OR itemtype.category = 'EQUIPMENT' 
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
        DB::statement("DROP VIEW itemtransfer_v;");
    }
}
