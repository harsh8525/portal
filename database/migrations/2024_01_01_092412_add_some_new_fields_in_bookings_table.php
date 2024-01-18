<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeNewFieldsInBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('pnr_number')->nullable()->comment('ticket PNR number')->after('supplier_booking_ref');
            $table->decimal('admin_sub_total', 10, 2)->default(0.00)->comment('admin booking all total price including tax and everything ')->after('is_guest');
            $table->decimal('admin_tax', 10, 2)->default(0.00)->comment('admin tax percentage value I.e 15.00, 19.50')->after('admin_sub_total');
            $table->decimal('admin_s_tax', 10, 2)->default(0.00)->comment('admin tax received by suppliers')->after('admin_tax');
            $table->decimal('admin_s_charge', 10, 2)->default(0.00)->comment('admin charges added by admin')->after('admin_s_tax');
            $table->decimal('admin_currency_conversion_rate', 10, 2)->default(0.00)->comment('admin store currency conversion rate at time of booking')->after('admin_s_charge');
            $table->decimal('admin_currency_markup', 10, 2)->default(0.00)->comment('admin store currency markup at time of booking')->after('admin_currency_conversion_rate');
            $table->renameColumn('supplier_currency', 'admin_currency');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('admin_currency', 'supplier_currency');
        });
    }
}
