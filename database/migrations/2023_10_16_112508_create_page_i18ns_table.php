<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_i18ns', function (Blueprint $table) {
            $table->id();
            $table->uuid('page_id');
            $table->string('page_code');
            $table->string('page_title');
            $table->string('page_content');
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('slug_url');
            $table->string('keywords');
            $table->string('language_code');
            $table->timestamps();
            
            $table->foreign('page_id')
              ->references('id')
              ->on('pages');

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
        Schema::dropIfExists('page_i18ns');
    }
}
