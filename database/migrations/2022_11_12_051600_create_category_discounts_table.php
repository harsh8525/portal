<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->comment('reference key from categories table');
            $table->enum('discount_base', ['qty','amt'])->default('amt')->comment('discount base either quantity(qty) or amount(amt)');
            //for dealer
            $table->double('dealer_min_val',9,2)->default(0.00)->comment('dealer minimum value of either quantity or amount');
            $table->integer('dealer_min_disc')->default(0)->comment('dealer minimum discount in %');
            $table->double('dealer_max_val',9,2)->default(0.00)->comment('dealer maximum value of either quantity or amount');
            $table->integer('dealer_max_disc')->default(0)->comment('dealer maximum discount in %');
            
            //for exclusive dealer
            $table->double('ex_dealer_min_val',9,2)->default(0.00)->comment('exclusive dealer minimum value of either quantity or amount');
            $table->integer('ex_dealer_min_disc')->default(0)->comment('exclusive dealer minimum discount in %');
            $table->double('ex_dealer_max_val',9,2)->default(0.00)->comment('exclusive dealer maximum value of either quantity or amount');
            $table->integer('ex_dealer_max_disc')->default(0)->comment('exclusive dealer maximum discount in %');
            
            //for channel partner
            $table->double('channel_partner_min_val',9,2)->default(0.00)->comment('channel partner minimum value of either quantity or amount');
            $table->integer('channel_partner_min_disc')->default(0)->comment('channel partner minimum discount in %');
            $table->double('channel_partner_max_val',9,2)->default(0.00)->comment('channel partner maximum value of either quantity or amount');
            $table->integer('channel_partner_max_disc')->default(0)->comment('channel partner maximum discount in %');
            
            //for distrubutor
            $table->double('dist_min_val',9,2)->default(0.00)->comment('distrubutor minimum value of either quantity or amount');
            $table->integer('dist_min_disc')->default(0)->comment('distrubutor minimum discount in %');
            $table->double('dist_max_val',9,2)->default(0.00)->comment('distrubutor maximum value of either quantity or amount');
            $table->integer('dist_max_disc')->default(0)->comment('distrubutor maximum discount in %');
            
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
        Schema::dropIfExists('category_discounts');
    }
}
