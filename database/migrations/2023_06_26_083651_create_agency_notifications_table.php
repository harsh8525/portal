<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_notifications', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("agency_id")->comment("reference id key of agencies table");
            $table->bigInteger("notification_entity_type_uuid")->comment("reference id key of notification_entity_types table");
            $table->text("user")->comment("coma seprated agency user, default at time of create agancy add user_id of that agency");
            $table->tinyInteger("status")->default(0)->comment("notification status");
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
        Schema::dropIfExists('agency_notifications');
    }
}
