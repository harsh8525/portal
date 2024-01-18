<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnFromPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('page_title');
            $table->dropColumn('page_content');
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_description');
            $table->dropColumn('keywords');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('page_title');
            $table->string('page_content');
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('keywords');
        });
    }
}
