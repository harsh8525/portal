<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationToCityI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('city_i18ns', function (Blueprint $table) {
//            if (Schema::hasForeign($table, 'city_id')){
                $table->dropForeign(['city_id']);            
//            }
            $table->foreign('city_id')
              ->references('id')
              ->on('cities')
              ->onDelete('cascade')
              ->onUpdate('cascade');

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
        Schema::table('city_i18ns', function (Blueprint $table) {
            //
        });
    }
}
