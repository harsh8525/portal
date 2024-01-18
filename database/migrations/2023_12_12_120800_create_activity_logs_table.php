<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->string('device_id')->nullable();
            $table->string('browser_name')->nullable();
            $table->json('request')->nullable();
            $table->json('response')->nullable();
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
        Schema::dropIfExists('customer_activity_logs');
    }
}
