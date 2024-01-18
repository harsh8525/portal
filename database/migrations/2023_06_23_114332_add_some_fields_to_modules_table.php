<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsToModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->tinyInteger('is_managerapp')->after('module_name')->default(0)->comment("is the module is for manager app or not. If is for manager app than set as 1 else set as 0");
            $table->tinyInteger('is_b2bapp')->after('is_managerapp')->default(0)->comment("is the module is for b2b app or not. If is for b2b app than set as 1 else set as 0");
            $table->tinyInteger('is_supplierapp')->after('is_b2bapp')->default(0)->comment("is the module is for supplier app or not. If is for supplier app than set as 1 else set as 0");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules', function (Blueprint $table) {
            //
        });
    }
}
