<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',60);
            $table->string('idApi',50);
            $table->dateTime('startDate');
            $table->dateTime('endDate');
            $table->unsignedBigInteger('itinerary_id')->nullable($value = false);
            $table->json('location');
            $table->string('photo',255);
            $table->timestamps();
            $table->foreign('itinerary_id')->references('id')->on('itineraries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destinations');
    }
}
