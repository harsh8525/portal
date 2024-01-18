<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airport_i18ns', function (Blueprint $table) {
            $table->id();
            $table->uuid('airport_id');
            $table->string('airport_name');
            $table->string('language_code');
            $table->timestamps();

            $table->foreign('airport_id')
              ->references('id')
              ->on('airports')
              ->onDelete('cascade')
              ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airport_i18ns');
    }
}
