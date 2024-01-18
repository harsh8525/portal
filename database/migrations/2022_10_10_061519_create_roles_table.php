<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('role name');
            $table->string('code')->unique()->comment('role code that identity of role, this is the copy of name with uppercase and replace space by underscore(_). Role code is not changable');
            $table->text('description')->comment('role description');
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
        Schema::dropIfExists('roles');
    }
}
