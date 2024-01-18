<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequestUrlToCustomerActivityLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::table('customer_activity_logs', function (Blueprint $table) {
            $table->string('request_url')->nullable()->after('browser_name');
        });
    }

    public function down()
    {
        Schema::table('customer_activity_logs', function (Blueprint $table) {
            $table->dropColumn('request_url');
        });
    }
}
