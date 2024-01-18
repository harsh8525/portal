<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerAddresses;

class StateCityChangeDataTypeInCustomerAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->uuid('state')->nullable()->change();

            // Change city column to BIGINT
            $table->uuid('city')->nullable()->change();

            $table->foreign('city')
            ->references('id')
            ->on('cities')  // Corrected reference table name
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->foreign('state')
            ->references('id')
            ->on('states')  // Corrected reference table name
            ->onDelete('cascade')
            ->onUpdate('cascade');
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
