<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeploymentview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW itemdeployment_v  AS  
            SELECT CONCAT(inventory.brand,inventory.model) AS 'brand' ,itemprofile.propertynumber,ticket_v.details,ticket_v.date
            FROM ticket_v 
                JOIN itemprofile
                    ON ticket_v.tag LIKE itemprofile.propertynumber
                JOIN inventory 
                    ON inventory.id = itemprofile.inventory_id
                JOIN itemtype 
                    ON inventory.itemtype_id = itemtype.id
            WHERE UCASE(tickettype) = UCASE('deployment')
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
        DB::statement("DROP VIEW itemdeployment_v;");
    }
}
