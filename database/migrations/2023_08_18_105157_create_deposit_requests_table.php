<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_requests', function (Blueprint $table) {
            $table->bigIncrements("id")->comment("Deposit request id");
            $table->bigInteger("request_id");
            $table->bigInteger("agency_id")->comment("Agency id");
            $table->bigInteger("user_uuid")->comment("User id");
            $table->float("amount",9,2)->comment("Amount");
            $table->date("deposit")->comment("Deposit date");
            $table->integer("core_payment_type_uuid")->comment("Core payment type id");
            $table->string("reference_no")->comment("Reference no")->nullable();
            $table->string("beneficiary_bank_name")->comment("Beneficiary bank detail of manager")->nullable();
            $table->string("account_number")->comment("Account Number")->nullable();
            $table->string("name_in_account")->comment("name as per account")->nullable();
            $table->string("bank_name")->comment("Bank name")->nullable();
            $table->string("bank_branch")->comment("Bank branch")->nullable();
            $table->string("card_no")->comment("cheque number")->nullable();
            $table->date("cheque_issue_date")->comment("cheque issue date")->nullable();
            $table->string("remarks")->comment("Remarks");
            $table->set("status", ['open','closed','decline'])->default('open')->comment("status");
            $table->string("document")->comment("scanned document copy")->nullable();
            $table->string("decline_reason")->comment("decline reason")->nullable();
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
        Schema::dropIfExists('deposit_requests');
    }
}
