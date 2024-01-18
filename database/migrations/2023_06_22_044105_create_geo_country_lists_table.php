<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoCountryListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_country_lists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('country_id')->comment("country id");
            $table->string('country_code',2)->comment("country code");
            $table->string('isd_code',10)->nullable()->comment("country isd code");
            $table->tinyInteger('is_domestic')->default(0)->comment("whether country flag is_domestic true(1) or false(0)");
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
        Schema::dropIfExists('geo_country_lists');
    }
}
