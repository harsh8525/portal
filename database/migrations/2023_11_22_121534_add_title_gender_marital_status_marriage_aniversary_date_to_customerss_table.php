<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleGenderMaritalStatusMarriageAniversaryDateToCustomerssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('states', function (Blueprint $table) {
            $table->index('iso_code');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('title', ['mr', 'mrs', 'miss'])->nullable()->after('id');
            $table->enum('gender', ['male', 'female'])->nullable()->after('date_of_birth');
            $table->enum('marital_status', ['married', 'single', 'other'])->nullable()->after('gender');
            $table->date('marriage_aniversary_date')->nullable()->after('marital_status');
        });
        Schema::table('customer_addresses', function (Blueprint $table) {

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            \App\Models\CustomerAddresses::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $table->foreign('city')
                ->references('iso_code')
                ->on('cities')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('state')
                ->references('iso_code')
                ->on('states')
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
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
