<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnNameInDepositRequstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deposit_requests', function (Blueprint $table) {
            $table->renameColumn('user_uuid', 'user_id');
            $table->renameColumn('core_payment_type_uuid', 'core_payment_type_id');
            $table->renameColumn('deposit', 'deposit_date');
            $table->renameColumn('beneficiary_bank_name', 'core_bank_id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deposit_requests', function (Blueprint $table) {
            //
        });
    }
}
