<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStateI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_i18ns', function (Blueprint $table) {
            $table->id();
            $table->uuid('state_id');
            $table->string('state_name');
            $table->string('language_code');
            $table->timestamps();
            
            $table->foreign('state_id')
              ->references('id')
              ->on('states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('state_i18ns');
    }
}
