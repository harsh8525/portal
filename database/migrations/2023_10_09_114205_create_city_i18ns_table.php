<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_i18ns', function (Blueprint $table) {
            $table->id();
            $table->uuid('city_id');
            $table->string('city_name');
            $table->string('language_code');
            $table->timestamps();
            
            $table->foreign('city_id')
              ->references('id')
              ->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city_i18ns');
    }
}
