<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingHotelRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_hotel_rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('booking_id')->comment('FK reference id key of bookings table')->nullable();
            $table->integer('hotel_code')->nullable();
            $table->string('hotel_name')->nullable();
            $table->string('hotel_coordinates')->nullable();
            $table->string('hotel_rating')->nullable();
            $table->string('hotel_contact')->nullable();
            $table->string('hotel_address')->nullable();
            $table->string('hotel_wildcards')->nullable();
            $table->string('hotel_images')->nullable();
            $table->string('room_type')->nullable();
            $table->string('room_code')->nullable();
            $table->string('room_name')->nullable();
            $table->text('room_facilities')->nullable();
            $table->longText('hotel_room_details')->nullable();
            $table->timestamps();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_hotel_rooms');
    }
}
