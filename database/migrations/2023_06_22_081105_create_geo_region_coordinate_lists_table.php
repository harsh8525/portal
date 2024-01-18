<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoRegionCoordinateListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_region_coordinate_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('region_id');
            $table->decimal('center_latitude',9,6);
            $table->decimal('center_longitude',9,6);
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
        Schema::dropIfExists('geo_region_coordinate_lists');
    }
}
