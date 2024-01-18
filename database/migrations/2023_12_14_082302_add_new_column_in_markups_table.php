<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnInMarkupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markups', function (Blueprint $table) {
            $table->dropForeign(['origin']);
            $table->dropForeign(['destination']);
            $table->dropColumn('origin');
            $table->dropColumn('destination');
            $table->dropColumn('from_base_fare');
            $table->dropColumn('to_base_fare');
            $table->dropColumn('comm_markup_on');
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
