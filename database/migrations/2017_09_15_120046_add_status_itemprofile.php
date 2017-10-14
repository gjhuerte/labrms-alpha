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
<<<<<<< HEAD
        Schema::table('itemprofile', function (Blueprint $table) {
            $table->dateTime('lent')->nullable();
            $table->dateTime('deployment')->nullable();
=======
        Schema::table('reservation', function (Blueprint $table) {
            $table->string('lent_status')->nullable();
            $table->string('deployment_status')->nullable()->setDefault('undeployed');
>>>>>>> origin/0.3
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
<<<<<<< HEAD
        Schema::table('itemprofile', function (Blueprint $table) {
            $table->dropColumn('lent');
            $table->dropColumn('deployment');
=======
        Schema::table('reservation', function (Blueprint $table) {
            $table->dropColumn('lent_status');
            $table->dropColumn('deployment_status');
>>>>>>> origin/0.3
        });
    }
}
