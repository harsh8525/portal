<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;

class ChangeDiscountPercentDatatypeToCategoryDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }
        Schema::table('category_discounts', function (Blueprint $table) {
            $table->double('dealer_min_disc',9,2)->change();
            $table->double('dealer_max_disc',9,2)->change();
            $table->double('ex_dealer_min_disc',9,2)->change();
            $table->double('ex_dealer_max_disc',9,2)->change();
            $table->double('channel_partner_min_disc',9,2)->change();
            $table->double('channel_partner_max_disc',9,2)->change();
            $table->double('dist_min_disc',9,2)->change();
            $table->double('dist_max_disc',9,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_discounts', function (Blueprint $table) {
            //
        });
    }
}
