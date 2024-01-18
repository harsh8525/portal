<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('banner_name');
            $table->enum('banner_type',['mobile','web'])->comment('this field to identify banner is for either for mobile app or web app')->default('mobile');
            $table->enum('media_type',['image','video'])->default('image');
            $table->string('banner_image')->nullable();
            $table->string('video_link')->nullable();
            $table->integer('category_id')->nullable();
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('sort_order')->nullable();
            $table->tinyInteger('status')->comment('0 for In-active, 1 for Active')->default('1');
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
        Schema::dropIfExists('home_banners');
        
    }
}
