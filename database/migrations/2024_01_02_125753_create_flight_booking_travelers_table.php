<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightBookingTravelersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_booking_travelers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('booking_id')->comment('FK reference id key of bookings table');
            $table->decimal('traveler_total', 10, 2)->default(0.00)->comment('traveler total price including base and tax');
            $table->decimal('traveler_base_fare', 10, 2)->default(0.00)->comment('traveler base price including tax');
            $table->decimal('traveler_tax', 10, 2)->default(0.00)->comment('traveler tax percentage value I.e 15.00, 19.50');
            $table->decimal('traveler_s_tax', 10, 2)->default(0.00)->comment('tax received by suppliers');
            $table->decimal('traveler_s_charge', 10, 2)->default(0.00)->comment('charges added by admin');
            $table->decimal('admin_total', 10, 2)->default(0.00)->comment('admin total price including base and tax');
            $table->decimal('admin_base_fare', 10, 2)->default(0.00)->comment('admin base price including tax');
            $table->decimal('admin_tax', 10, 2)->default(0.00)->comment('admin tax percentage value I.e 15.00, 19.50');
            $table->decimal('admin_s_tax', 10, 2)->default(0.00)->comment('tax received by suppliers');
            $table->decimal('admin_s_charge', 10, 2)->default(0.00)->comment('charges added by admin');
            $table->string('traveler_currency')->nullable()->comment('store traveler currency code');
            $table->string('admin_currency')->nullable()->comment('store admin currency code');
            $table->string('booking_class')->nullable()->comment('store traveler booking class');
            $table->string('booking_cabin')->nullable()->comment('store traveler booking cabin');
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
        Schema::dropIfExists('flight_booking_travelers');
    }
}
