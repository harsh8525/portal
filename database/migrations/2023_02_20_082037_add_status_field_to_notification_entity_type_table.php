<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusFieldToNotificationEntityTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_entity_types', function (Blueprint $table) {
            $table->tinyInteger('status')->comment('0 for In-active, 1 for Active')->after('admin_user')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_entity_types', function (Blueprint $table) {
            //
        });
    }
}
