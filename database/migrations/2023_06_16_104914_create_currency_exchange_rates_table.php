<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency_code',5)->comment("From Currency Code");
            $table->string('to_currency_code',5)->comment("To Currency Code");
            $table->double('exchange_rate')->default(0)->comment("Currency Exchange Rate");
            $table->double('margin')->default(0)->comment("Margin in Percentage	");
            $table->tinyInteger('update_type')->default(1)->comment("1=auto,2=manual");
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
        Schema::dropIfExists('currency_exchange_rates');
    }
}
