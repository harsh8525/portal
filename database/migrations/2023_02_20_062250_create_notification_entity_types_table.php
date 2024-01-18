<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationEntityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_entity_types', function (Blueprint $table) {
            $table->id();
            $table->string('entity',50);
            $table->string('entity_type',100);
            $table->string('entity_code',50)->unique();
            $table->string('text',150);
            $table->string('description',150);
            $table->tinyInteger('is_app_user')->default(1);
            $table->string('admin_user')->default('1')->comment('coma seprated admin users i.e 1,3,4');
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
        Schema::dropIfExists('notification_entity_types');
    }
}
