<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusStopDistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_stop_distances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bus_stop_from_id');
            $table->unsignedBigInteger('bus_stop_to_id');
            $table->float('distance_in_km');
            $table->timestamps();

            $table->foreign('bus_stop_from_id')->references('id')->on('bus_stops');
            $table->foreign('bus_stop_to_id')->references('id')->on('bus_stops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bus_stop_distances');
    }
}
