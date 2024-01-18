<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('host');
            $table->string('username');
            $table->integer('user_total_attempts')->default(0);
            $table->integer('host_total_attempts')->default(0);
            $table->dateTime('attempt_at');
            $table->dateTime('next_login_available_at')->nullable()->default(null);
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
        Schema::dropIfExists('login_attempts');
    }
}
