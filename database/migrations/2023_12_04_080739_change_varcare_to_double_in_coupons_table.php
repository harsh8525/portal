<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeVarcareToDoubleInCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->decimal('coupon_amount',10,2)->nullable()->change();
            $table->decimal('maximum_spend',10,2)->nullable()->change();
            $table->decimal('minimum_spend',10,2)->nullable()->change();
            $table->integer('limit_per_coupon')->nullable()->change();
            $table->integer('limit_per_customer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            //
        });
    }
}
