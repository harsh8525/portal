<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeInPageI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_i18ns', function (Blueprint $table) {
            $table->dropForeign(['page_id']);
            $table->foreign('page_id')
            ->references('id')
            ->on('pages')
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
        Schema::table('page_i18ns', function (Blueprint $table) {
            //
        });
    }
}
