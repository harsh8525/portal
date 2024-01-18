<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoreServiceTypeIdFieldToCoreSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_suppliers', function (Blueprint $table) {
            $table->integer("core_service_type_id")->default(0)->after("id");
            $table->string("code")->nullable()->after("name");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_suppliers', function (Blueprint $table) {
            //
        });
    }
}
