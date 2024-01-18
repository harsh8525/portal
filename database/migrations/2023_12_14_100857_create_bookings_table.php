<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('booking_ref');
            $table->string('supplier_booking_ref');
            $table->bigInteger('supplier_id')->comment('reference id key of core suppliers table')->unsigned();
            $table->date('booking_date');
            $table->bigInteger('service_id')->comment('reference id key of core service types table')->unsigned();
            $table->uuid('customer_id')->comment('reference id key of customers table');
            $table->bigInteger('agency_id')->comment('agency id = 0 is for B2C agency will be default')->unsigned();
            $table->string('description')->comment('I.e for flight “Delhi to Longon (Round Trip)”, for hotel “Hotel Amar Inn”');
            $table->decimal('sub_total', 10, 2)->default(0.00)->comment('booking all total price including tax and everything');
            $table->decimal('tax', 10, 2)->default(0.00)->comment('tax percentage value I.e 15.00, 19.50');
            $table->decimal('s_tax', 10, 2)->default(0.00)->comment('tax received by suppliers');
            $table->decimal('s_charge', 10, 2)->default(0.00)->comment('charges added by admin');

            $table->decimal('s_discount_type', 10, 2)->default(0.00)->comment('fixed or percent');
            $table->decimal('s_discount_value', 10, 2)->default(0.00)->comment('discount value');
            $table->decimal('s_discount', 10, 2)->default(0.00)->comment('discount amount');
            $table->decimal('t_discount_type', 10, 2)->default(0.00)->comment('fixed or percent');
            $table->decimal('t_discount_value', 10, 2)->default(0.00)->comment('discount value');
            $table->decimal('t_discount', 10, 2)->default(0.00)->comment('discount amount');
            $table->decimal('t_markup_type', 10, 2)->default(0.00)->comment('fixed or percent');
            $table->decimal('t_markup_value', 10, 2)->default(0.00)->comment('markup value');
            $table->decimal('t_markup', 10, 2)->default(0.00)->comment('markup amount');
            $table->text('booking_details')->nullable()->comment('store whole json value that receives from suppliers');
            $table->enum('booking_status', ['pending', 'processing', 'confirmed', 'failed', 'cancelled'])
                  ->nullable();

            $table->string('supplier_currency')->nullable()->comment('store supplier currency code');
            $table->string('customer_currency')->nullable()->comment('store customer currency code');
            $table->decimal('currency_conversion_rate', 10, 2)->default(0.00)->comment('store currency conversion rate at time of booking');
            $table->decimal('currency_markup', 10, 2)->default(0.00)->comment('store currency markup at time of booking');

            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('core_suppliers')->onDelete('cascade')
            ->onUpdate('cascade');
            $table->foreign('service_id')->references('id')->on('core_service_types')->onDelete('cascade')
            ->onUpdate('cascade');
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
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
        Schema::dropIfExists('bookings');
    }
}
