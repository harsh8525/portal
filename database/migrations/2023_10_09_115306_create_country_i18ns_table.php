<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_i18ns', function (Blueprint $table) {
            $table->id();
            $table->uuid('country_id');
            $table->string('country_name');
            $table->string('language_code');
            $table->timestamps();
            
            $table->foreign('country_id')
              ->references('id')
              ->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_i18ns');
    }
}
