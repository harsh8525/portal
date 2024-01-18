<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('this is primary key');
            $table->string('name',100)->comment('the name of category');
            $table->bigInteger('parent_id')->default(0)->comment('if this is child category than store the parent category id');
            $table->text('description')->nullable()->comment('description of category');
            $table->string('image')->comment('category image');
            $table->integer('sort_order')->default(0)->comment('display order of category');
            $table->integer('level')->default(0)->comment('this field contain the level of category');
            $table->tinyInteger('status')->default(1)->comment('0 = Inactive, 1 = Active,2 = Deleted');
            $table->string('meta_title')->nullable()->comment('this field contain meta title for seo purpose');
            $table->text('meta_description')->nullable()->comment('this field contain meta description for seo purpose');
            $table->string('slug_url')->nullable()->comment('this field contain slug url for seo purpose');
            $table->text('keywords')->nullable()->comment('this field contain meta keywords for seo purpose');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
