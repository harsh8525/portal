<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSomeFieldsForUserLoginAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('user_login_attempts', 'user_total_attempts'))
        {
            Schema::table('user_login_attempts', function (Blueprint $table)
            {
                $table->dropColumn('user_total_attempts');
            });
        }
        
        if (Schema::hasColumn('user_login_attempts', 'host_total_attempts'))
        {
            Schema::table('user_login_attempts', function (Blueprint $table)
            {
                $table->dropColumn('host_total_attempts');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
