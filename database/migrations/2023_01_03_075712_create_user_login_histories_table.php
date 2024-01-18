<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoginHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger("is_admin")->default(0)->comment("user is admin or not 1 = admin user 0 = application user");
            $table->bigInteger("user_id");
            $table->string('device_type')->comment("device type should be type or class of Device");
            $table->string('device_version')->comment("device version should be compatible with device type");
            $table->string('device_token');
            $table->string('device_id')->comment("device id should be unique, anonymous identifier assigned to a device");
            $table->string('ip_address')->comment("IP address should be unique address that identifies a device on the internet or a local network");
            $table->string('app_version');
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
        Schema::dropIfExists('user_login_histories');
    }
}
