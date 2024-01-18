<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerAddresses;

class ChangeDataTypeInCustomerAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Truncate the table
            CustomerAddresses::truncate();

            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $table->dropForeign(['state']);
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            //
        });
    }
}
