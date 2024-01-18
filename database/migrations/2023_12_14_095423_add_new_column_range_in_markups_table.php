<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnRangeInMarkupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markups', function (Blueprint $table) {
            $table->string('rule_name')->nullable()->after('id');
            $table->string('origin_criteria')->nullable()->after('service_type_id');
            $table->string('origin_name')->nullable()->after('service_type_id');
            $table->string('destination_criteria')->nullable()->after('service_type_id');
            $table->string('destination_name')->nullable()->after('service_type_id');
            $table->decimal('from_price_range', 10, 2)->nullable()->after('pax_type');
            $table->decimal('to_price_range', 10, 2)->nullable()->after('pax_type');
            $table->enum('comm_markup_on', ['base_fare', 'base_fare_yq', 'net_fare', 'total_fare'])
                  ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('markups', function (Blueprint $table) {
            //
        });
    }
}
