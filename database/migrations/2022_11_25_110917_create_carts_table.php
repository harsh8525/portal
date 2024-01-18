<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('app_user_id')->comment('reference key of app_users table');
            $table->string('user_type')->comment('user type text');
            $table->bigInteger('category_id')->comment('reference key of product category id');
            $table->bigInteger('parent_category_id')->comment("reference key of category's parent_id");
            $table->bigInteger('product_id')->comment('reference key of products table');
            $table->bigInteger('product_attribute_id')->comment('reference key of product attributes table');
            $table->bigInteger('product_finish_master_id')->comment('reference key of product finish master table');
            $table->double('mrp',9,2)->comment("product attribute mrp");
            $table->double('price',9,2)->comment("user type viz product price");
            $table->integer('quantity')->comment("product quantity in cart");
            $table->double('category_discount_price',9,2)->comment("single quantity category discount price on base of total amount or quantity");
            $table->integer('product_price_discount')->comment("product price discount value");
            $table->integer('product_offer_discount')->comment("product offer discount value");
            $table->double('product_discount_price',9,2)->comment("single quantity product discount price on base of offer added");
            $table->integer('product_gst_value')->comment("product gst percent value");
            $table->double('product_gst_price',9,2)->comment("single quantity product gst total amount");
            $table->double('total_price',9,2)->comment("total product price (price * quantity)");
            $table->double('total_category_discount_price',9,2)->comment("total category discount price (category_discount_price * quantity)");
            $table->double('total_product_discount_price',9,2)->comment("total product discount price (product_discount_price * quantity)");
            $table->double('total_product_gst_price',9,2)->comment("total product gst price (product_gst_price * quantity)");
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
        Schema::table('carts', function (Blueprint $table) {
            //
        });
    }
}
