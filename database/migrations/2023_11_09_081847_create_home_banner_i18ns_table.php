<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeBannerI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_banner_i18ns', function (Blueprint $table) {
            $table->id();
            $table->uuid('banner_id');
            $table->string('banner_title');
            $table->string('language_code');
            $table->timestamps();

            $table->foreign('banner_id')
                ->references('id')
                ->on('home_banners');

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
        Schema::dropIfExists('home_banner_i18ns');
    }
}
