<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropGeoAirlinesGeoCountryListsGeoRegionCoordinateListsGeoRegionListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('geo_airlines');
        Schema::dropIfExists('geo_country_lists');
        Schema::dropIfExists('geo_region_coordinate_lists');
        Schema::dropIfExists('geo_region_lists');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
