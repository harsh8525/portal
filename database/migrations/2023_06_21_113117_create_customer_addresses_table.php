<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->comment("reference key of customer table id");
            $table->text("address1")->nullable()->comment("customer address 1");
            $table->text("address2")->nullable()->comment("customer address 2");
            $table->bigInteger("country")->nullable()->comment("reference key of country table id");
            $table->string("state")->nullable()->comment("customer address state");
            $table->string("city")->nullable()->comment("customer address city");
            $table->string("pincode")->nullable()->comment("customer address pincode");
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
        Schema::dropIfExists('customer_addresses');
    }
}
