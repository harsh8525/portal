<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationToStateI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('state_i18ns', function (Blueprint $table) {
//            if (Schema::hasForeign($table, 'state_id')){
                $table->dropForeign(['state_id']);            
//            }
            $table->foreign('state_id')
              ->references('id')
              ->on('states')
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
        Schema::table('state_i18ns', function (Blueprint $table) {
            //
        });
    }
}
