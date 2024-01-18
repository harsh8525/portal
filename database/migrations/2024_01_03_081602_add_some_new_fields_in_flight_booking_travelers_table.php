<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeNewFieldsInFlightBookingTravelersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flight_booking_travelers', function (Blueprint $table) {
            $table->integer('traveler_id')->comment('traveler id')->after('booking_cabin');
            $table->string('traveler_type')->comment('traveler type')->after('traveler_id');
            $table->string('gender')->comment('traveler gender either male or female')->after('traveler_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flight_booking_travelers', function (Blueprint $table) {
            //
        });
    }
}
