<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('user_type',['dealer','exclusive_dealer','channel_partner','distributor','contractor'])->default('dealer');
            $table->enum('user_all',['true','false'])->default('false');
            $table->bigInteger('category_id')->nullable();
            $table->string('notification_image');
            $table->string('notification_title',50);
            $table->string('notification_message',150);
            $table->date('date');
            $table->time('time');
            $table->integer('status')->comment('0 = In Active, 1 = Active, 2 = Delete');
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
        Schema::dropIfExists('manual_notifications');
    }
}
