<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('customer_type')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('coupon_amount')->nullable();
            $table->string('discount_type')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('maximum_spend')->nullable();
            $table->string('minimum_spend')->nullable();
            $table->bigInteger('service_type_id')->nullable()->comment('reference id key of core service types table')->unsigned();
            $table->string('customer')->nullable();
            $table->string('limit_per_coupon')->nullable();
            $table->string('limit_per_customer')->nullable();
            $table->string('upload_image')->comment('Coupon Image')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
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
        Schema::dropIfExists('coupons');
    }
}
