<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnMaxMobileNumberLengthInGeoCountryListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('geo_country_lists', function (Blueprint $table) {
            $table->integer('max_mobile_number_length')->comment('Maximum Mobile Number Length')->after('is_domestic');
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
