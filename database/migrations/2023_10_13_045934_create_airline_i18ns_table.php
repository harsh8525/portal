<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirlineI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airline_i18ns', function (Blueprint $table) {
            $table->id();
            $table->uuid('airline_id');
            $table->string('airline_name');
            $table->string('language_code');
            $table->timestamps();
            
            $table->foreign('airline_id')
              ->references('id')
              ->on('airlines');

            $table->foreign('language_code')
            ->references('language_code')
            ->on('core_languages')
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
        Schema::dropIfExists('airline_i18ns');
    }
}
