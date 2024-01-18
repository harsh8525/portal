<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('group_code')->unique()->comment('group code');
            $table->string('group_name')->unique()->comment('group name');
            $table->string('module_code')->unique()->comment('module code');
            $table->string('module_name')->unique()->comment('module name');
            $table->integer('sort_order')->default(0)->comment('module sorting order');
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
        Schema::dropIfExists('modules');
    }
}
