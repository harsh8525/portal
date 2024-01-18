<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyPaymentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_payment_types', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("agency_id")->comment("reference id key of agencies table");
            $table->bigInteger("core_payment_type_id")->comment("reference id key of core_payment_types table");
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
        Schema::dropIfExists('agency_payment_types');
    }
}
