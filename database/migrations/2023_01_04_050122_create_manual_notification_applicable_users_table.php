<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualNotificationApplicableUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_notification_applicable_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('manual_notification_id');
            $table->enum('user_type',['dealer','exclusive_dealer','channel_partner','distributor','contractor'])->default('dealer');
            $table->bigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manual_notification_applicable_users');
    }
}
