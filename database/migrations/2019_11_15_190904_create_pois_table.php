<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePOIsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pois', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',60);
            $table->string('idApi',50);
            $table->dateTime('startDate');
            // $table->dateTime('endDate');
            $table->unsignedBigInteger('destination_id')->nullable($value = false);
            $table->json('location');
            //$table->float('lat');
            //$table->float('lng');
            $table->string('photo');
            $table->integer('duration');
            $table->timestamps();
            $table->foreign('destination_id')->references('id')->on('destinations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pois');
    }
}
