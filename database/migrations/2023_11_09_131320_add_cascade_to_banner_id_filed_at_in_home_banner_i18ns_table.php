<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeToBannerIdFiledAtInHomeBannerI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_banner_i18ns', function (Blueprint $table) {
            $table->dropForeign(['banner_id']);
            $table->foreign('banner_id')
                ->references('id')
                ->on('home_banners')
                ->onDelete('cascade')
                ->onUpdate('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_banner_i18ns', function (Blueprint $table) {
            //
        });
    }
}
