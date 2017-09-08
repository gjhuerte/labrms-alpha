<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up() 
    {
        DB::statement("
            CREATE VIEW item_v 
            AS 
            SELECT
                itemtype.name as 'itemtype',
                itemtype.category,
                inventory.brand,
                inventory.model,
                inventory.details,
                itemprofile.propertynumber,
                itemprofile.serialnumber,
                itemprofile.status
            FROM inventory
            JOIN itemtype
                ON inventory.itemtype_id = itemtype.id
            JOIN itemprofile
                ON itemprofile.inventory_id = inventory.id;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS item_v;");
    }

}
