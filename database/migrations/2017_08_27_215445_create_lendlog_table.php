<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLendlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lendlog', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('courseyearsection',100)->nullable();
            $table->string('facultyincharge',100)->nullable();
            $table->string('location');
            $table->datetime('timein');
            $table->datetime('timeout')->nullable();
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('itemprofile')
                                    ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lendlog');
    }
}
