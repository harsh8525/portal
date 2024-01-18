<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment("code of currency");
            $table->string('name')->comment("name of currency");
            $table->string('symbol')->comment("symbol of currency");
            $table->string('thousand_separator')->comment("thousand separator");
            $table->string('decimal_separator')->comment("decimal separator");
            $table->tinyInteger('is_allowed')->default(0)->comment("allowed currency in application");
            $table->tinyInteger('supplier_allowed_currency')->default(0)->comment("allowed currency for supplier users");
            $table->tinyInteger('b2b_allowed_currency')->default(0)->comment("allowed currency for b2b users");
            $table->tinyInteger('is_base_currency')->default(0)->comment("base currency for conversion rate");
            $table->tinyInteger('is_top_cur')->default(0)->comment("top currency to display as top");
            $table->tinyInteger('is_default')->default(0)->comment("default currency to show amount in default currency code");
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
        Schema::dropIfExists('currencies');
    }
}
