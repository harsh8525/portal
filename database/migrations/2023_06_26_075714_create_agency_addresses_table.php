<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_addresses', function (Blueprint $table) {
            $table->bigIncrements("id")->comment("primary auto increment");
            $table->bigInteger("agency_id")->comment("reference id key of agencies table");
            $table->string("address1")->comment("agency address 1");
            $table->string("address2")->nullable()->comment("agency address 2");
            $table->bigInteger("country_id")->comment("reference id key of geo_countries table");
            $table->string("state")->comment("agency address state");
            $table->string("city")->comment("agency address city");
            $table->string("pincode")->comment("agency address pincode");
            
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
        Schema::dropIfExists('agency_addresses');
    }
}
