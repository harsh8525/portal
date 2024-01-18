<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerAddresses;

class AddDropForegnKeyOfCityInCustomerAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {

            



            // Disable foreign key checks to truncate the table
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Truncate the table
            CustomerAddresses::truncate();

            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $table->dropForeign(['city']);

            // Drop the existing foreign key constraint

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
