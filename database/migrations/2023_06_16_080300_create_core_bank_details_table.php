<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('bank_code');
            $table->string('beneficiary_name');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('bank_address');
            $table->string('swift_code');
            $table->string('iban_number');
            $table->string('sort_code');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('core_bank_details');
    }
}
