<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateMarkupsTable extends Migration
{
    use SoftDeletes;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('service_type_id')->nullable()->comment('reference id key of core service types table')->unsigned();
            $table->uuid('origin')->nullable()->comment('reference id key of airport table');
            $table->uuid('destination')->nullable()->comment('reference id key of airport table');
            $table->date('from_booking_date')->nullable();
            $table->date('to_booking_date')->nullable();
            $table->date('from_travel_date')->nullable();
            $table->date('to_travel_date')->nullable();
            $table->string('booking_class')->nullable();
            $table->string('cabin_class')->nullable();
            $table->string('trip_type')->nullable();
            $table->string('pax_type')->nullable();
            $table->decimal('from_base_fare', 10, 2)->nullable();
            $table->decimal('to_base_fare', 10, 2)->nullable();
            $table->enum('fare_type', ['commission', 'net_fare'])->nullable();
            $table->enum('b2c_markup_type', ['percentage', 'fixed_amount'])->nullable();
            $table->decimal('b2c_markup', 10, 2)->nullable();
            $table->enum('b2b_markup_type', ['percentage', 'fixed_amount'])->nullable();
            $table->decimal('b2b_markup', 10, 2)->nullable();
            $table->enum('comm_markup_on', ['base_fare', 'base_fare_yq', 'net_fare'])->nullable();
            $table->integer('priority')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('origin')->references('id')->on('airports');
            $table->foreign('destination')->references('id')->on('airports');
            $table->foreign('service_type_id')->references('id')->on('core_service_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markups');
    }
}
