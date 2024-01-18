<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturedFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('featured_flights', function (Blueprint $table) {
            $table->id();
            $table->string("airline_code")->comment("airline code");
            $table->string("from_airport_code")->comment("from airport code");
            $table->string("to_airport_code")->comment("to airport code");
            $table->double("price")->comment("display price");
            $table->tinyInteger("status")->comment("status 1=active 0=inactive");
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
        Schema::dropIfExists('featured_flights');
    }
}
