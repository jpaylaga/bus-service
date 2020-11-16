<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bus_stop_distances', function (Blueprint $table) {
            $table->integer('eta_in_mins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bus_stop_distances', function (Blueprint $table) {
            $table->dropColumn('eta_in_mins');
        });
    }
}
