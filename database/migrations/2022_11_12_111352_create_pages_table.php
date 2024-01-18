<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
	    $table->string('page_code');
	    $table->string('page_title');
	    $table->string('slug_url');
	    $table->text('page_content');
	    $table->tinyInteger('status')->comment('0 for In-active, 1 for Active')->default('1');
        $table->string('meta_title');
        $table->text('meta_description');
        $table->text('keywords');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
