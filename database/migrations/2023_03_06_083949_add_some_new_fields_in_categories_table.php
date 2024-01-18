<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeNewFieldsInCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->tinyInteger('dealer')->nullable()->default(1)->comment('is category visible for dealer?')->after('status');
            $table->tinyInteger('exclusive_dealer')->nullable()->default(1)->comment('is category visible for exclusive dealer?')->after('dealer');
            $table->tinyInteger('channel_partner')->nullable()->default(1)->comment('is category visible for channel partner?')->after('exclusive_dealer');
            $table->tinyInteger('distributor')->nullable()->default(1)->comment('is category visible for distributor?')->after('channel_partner');
            $table->tinyInteger('contractor')->nullable()->default(1)->comment('is category visible for contractor?')->after('distributor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
}
