<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortOrderAndCodeToCoreServiceTypesNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_service_types', function (Blueprint $table) {
            $table->integer('sort_order')->nullable()->after('image');
            $table->string('code')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_service_types', function (Blueprint $table) {
            //
        });
    }
}
