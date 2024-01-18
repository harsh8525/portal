<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_i18ns', function (Blueprint $table) {
            $table->id();
            $table->uuid('coupon_id');
            $table->string('coupon_name');
            $table->string('language_code');
            $table->timestamps();

            $table->foreign('coupon_id')
                ->references('id')
                ->on('coupons')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('language_code')
                ->references('language_code')
                ->on('core_languages')
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
        Schema::dropIfExists('coupon_i18ns');
    }
}
