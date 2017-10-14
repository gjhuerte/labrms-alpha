<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemtrailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemtrail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('propertynumber',100);
            $table->string('oldlocation',100);
            $table->string('newlocation',100);
            $table->datetime('timetransfered');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itemtrail');
    }
}
