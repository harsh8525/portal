<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToMarkupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markups', function (Blueprint $table) {

            $table->date('from_check_in_date')->nullable()->comment('key of hotel markups')->after('priority');
            $table->date('to_check_in_date')->nullable()->comment('key of hotel markups')->after('from_check_in_date');
            $table->string('star_category')->nullable()->comment('key of hotel markups')->after('to_check_in_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('markups', function (Blueprint $table) {
            //
        });
    }
}
