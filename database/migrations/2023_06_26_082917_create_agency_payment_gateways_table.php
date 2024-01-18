<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyPaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_payment_gateways', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("agency_id")->comment("reference id key of agencies table");
            $table->bigInteger("core_payment_gateway_id")->comment("reference id key of core_payment_gateways table");
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
        Schema::dropIfExists('agency_payment_gateways');
    }
}
