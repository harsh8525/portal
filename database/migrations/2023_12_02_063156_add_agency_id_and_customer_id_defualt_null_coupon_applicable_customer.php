<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgencyIdAndCustomerIdDefualtNullCouponApplicableCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_applicable_customer', function (Blueprint $table) {
            $table->uuid('customer_id')->nullable()->change();
            $table->bigInteger('agency_id')->nullable()->comment('reference id key of agencies table')->unsigned()->after('customer_id');

            $table->foreign('agency_id')
                ->references('id')
                ->on('agencies')
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
        Schema::table('coupon_applicable_customer', function (Blueprint $table) {
            //
        });
    }
}
