<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkstationview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW workstation_v  AS  
            SELECT 
                pc.name,
                systemunit.propertynumber AS systemunit_propertynumber,
                systemunit_inv.brand AS systemunit_brand,
                systemunit_inv.model AS systemunit_model,
                systemunit_inv.details AS systemunit_specs,
                systemunit.location AS location,
                monitor.propertynumber AS monitor_propertynumber,
                monitor_inv.brand AS monitor_brand,
                monitor_inv.model AS monitor_model,
                monitor_inv.details AS monitor_specs,
                keyboard.propertynumber AS keyboard_propertynumber,
                keyboard_inv.brand AS keyboard_brand,
                keyboard_inv.model AS keyboard_model,
                avr.propertynumber AS avr_propertynumber,
                avr_inv.brand AS avr_brand,
                avr_inv.model AS avr_model,
                pc.mouse AS mouse,
                pc.oskey AS oskey
            FROM pc          
                JOIN itemprofile AS systemunit 
                    ON pc.systemunit_id = systemunit.id
                JOIN inventory AS systemunit_inv
                    ON systemunit_inv.id = systemunit.inventory_id 
                JOIN itemprofile AS monitor 
                    ON pc.monitor_id = monitor.id
                JOIN inventory AS monitor_inv 
                    ON monitor_inv.id = monitor.inventory_id
                JOIN itemprofile AS keyboard 
                    ON pc.keyboard_id = keyboard.id
                JOIN inventory AS keyboard_inv 
                    ON keyboard_inv.id = keyboard.inventory_id
                JOIN itemprofile AS avr 
                    ON pc.avr_id = avr.id
                JOIN inventory AS avr_inv 
                    ON avr_inv.id = avr.inventory_id
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
        DB::statement("DROP VIEW workstation_v;");
    }
}
