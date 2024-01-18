<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoAirportListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_airport_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('airport_code',3)->unique()->comment("airport code");
            $table->string('airport_name')->unique()->comment("airport name");
            $table->decimal("latitude", 9, 6)->comment("airport latitude");
            $table->decimal("longitude", 9, 6)->comment("airport latitude");
            $table->bigInteger("main_city_id")->comment("airport city id, reference key of geo_region_list table");
            $table->string('country_code',3)->comment("airport country code");
            $table->tinyInteger("is_active")->default(1)->comment("1=active, 2=inactive");            
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
        Schema::dropIfExists('geo_airport_lists');
    }
}
