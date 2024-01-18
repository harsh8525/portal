<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCascsdeInAirlinei18ns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('airline_i18ns', function (Blueprint $table) {
            $table->dropForeign(['airline_id']);            
          
                        $table->foreign('airline_id')
                          ->references('id')
                          ->on('airlines')
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
        Schema::table('airline_i18ns', function (Blueprint $table) {
            //
        });
    }
}
