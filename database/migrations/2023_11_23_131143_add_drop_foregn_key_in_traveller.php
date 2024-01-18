<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDropForegnKeyInTraveller extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers_traveller', function (Blueprint $table) {
            // Drop the foreign key constraints
            $table->dropForeign(['country_id']);
            $table->dropForeign(['nationality_id']);

            // Recreate foreign key constraints
            $table->foreign('country_id')
                ->references('iso_code')
                ->on('countries')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('nationality_id')
                ->references('iso_code')
                ->on('countries')
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
        Schema::table('customers_traveller', function (Blueprint $table) {
            //
        });
    }
}
