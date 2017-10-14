<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLostAndFoundItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lost_and_found_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identifier')->unique();
            $table->string('description')->nullable();
            $table->string('imagepath')->nullable();
            $table->datetime('datefound');
            $table->string('claimant')->nullable();
            $table->string('claimantdesc')->nullable();
            $table->datetime('dateclaimed')->nullable();
            $table->string('status')->nullable()->default('unclaimed');
            $table->string('addedby')->nullable();
            $table->string('processedby')->nullable();
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
        Schema::dropIfExists('lost_and_found_items');
    }
}
