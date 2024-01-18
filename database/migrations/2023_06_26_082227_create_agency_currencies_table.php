<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_currencies', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("agency_id")->comment("reference id key of agencies table");
            $table->bigInteger("currency_id")->comment("reference id key of currencies table");
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
        Schema::dropIfExists('agency_currencies');
    }
}
