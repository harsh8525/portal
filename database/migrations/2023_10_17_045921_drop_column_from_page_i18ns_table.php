<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnFromPageI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_i18ns', function (Blueprint $table) {
            $table->dropColumn('page_code');
            $table->dropColumn('slug_url');
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
            $table->dropColumn('page_code');
            $table->string('slug_url');
        });
    }
}
