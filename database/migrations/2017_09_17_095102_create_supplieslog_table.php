<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplieslogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplylendlog', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('courseyearsection',100)->nullable();
            $table->string('facultyincharge',100)->nullable();
            $table->string('location');
            $table->datetime('timein');
            $table->datetime('timeout')->nullable();
            $table->integer('supply_id')->unsigned();
            $table->foreign('supply_id')->references('id')->on('supply')
                                    ->onUpdate('cascade');
            $table->integer('quantity')->nullable();
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
        Schema::dropIfExists('supplylendlog');
    }
}
