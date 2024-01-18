<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDeviceOsAndLastLoginDateInToUserLoginHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_login_histories', function (Blueprint $table) {
            $table->string('device_os')->comment("specify that which OS is used in the device")->after('ip_address');
            $table->date('last_login_date')->after('device_os');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_login_histories', function (Blueprint $table) {
            //
        });
    }
}
