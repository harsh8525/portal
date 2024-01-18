<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPagesTableIfExists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            Schema::dropIfExists('pages');
            Schema::create('pages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('page_code');
                $table->string('page_title');
                $table->text('page_content');
                $table->tinyInteger('status')->comment('0 for In-active, 1 for Active')->default('1');
                $table->string('meta_title')->comment('this field contain meta title for seo purpose');
                $table->text('meta_description')->comment('this field contain meta description for seo purpose');
                $table->string('slug_url');
                $table->text('keywords')->comment('this field contain meta keywords for seo purpose');
                $table->timestamps();
                $table->softDeletes();
            });
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
            //
        });
    }
}
