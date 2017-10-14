<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusItemprofile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itemprofile', function (Blueprint $table) {
            $table->dateTime('lent')->nullable();
            $table->dateTime('deployment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itemprofile', function (Blueprint $table) {
            $table->dropColumn('lent');
            $table->dropColumn('deployment');
        });
    }
}
