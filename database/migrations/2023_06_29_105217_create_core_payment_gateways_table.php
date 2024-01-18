<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorePaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string("name")->comment("payment gateway name");
            $table->text("description")->nullable()->comment("payment gateway description");
            $table->string("logo")->comment("payment gateway logo");
            $table->string("api_url")->comment("payment module URL");
            $table->text("credential")->nullable()->comment("payment gateway credentials in json array format");
            $table->tinyInteger("is_active")->default(1)->comment("payment gateway is active flag");
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
        Schema::dropIfExists('core_payment_gateways');
    }
}
