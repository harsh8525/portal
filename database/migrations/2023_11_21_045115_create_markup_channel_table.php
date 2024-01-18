<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkupChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markups_channel', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('markups_id')->nullable()->comment('reference id key of markups table');
            $table->enum('channel', ['back_office', 'b2c', 'b2b', 'mobile'])->nullable();
            $table->timestamps();

            $table->foreign('markups_id')->references('id')->on('markups')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markups_channel');
    }
}
