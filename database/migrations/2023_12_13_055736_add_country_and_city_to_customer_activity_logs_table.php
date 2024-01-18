<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryAndCityToCustomerActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_activity_logs', function (Blueprint $table) {
            $table->string('country')->nullable()->after('browser_name');
            $table->string('city')->nullable()->after('country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_activity_logs', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('city');
        });
    }
}
