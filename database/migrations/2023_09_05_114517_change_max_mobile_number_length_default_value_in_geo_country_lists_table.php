<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMaxMobileNumberLengthDefaultValueInGeoCountryListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('geo_country_lists', function (Blueprint $table) {
            $table->integer('max_mobile_number_length')->default(10)->comment('Maximum Mobile Number Length')->change(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('geo_country_lists', function (Blueprint $table) {
            //
        });
    }
}
