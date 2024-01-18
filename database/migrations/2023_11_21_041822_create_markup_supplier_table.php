<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkupSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markups_supplier', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('markups_id')->nullable()->comment('reference id key of markups table');
            $table->bigInteger('supplier_id')->nullable()->comment('reference id key of core supplier table')->unsigned();
            $table->timestamps();

            $table->foreign('markups_id')->references('id')->on('markups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('supplier_id')->references('id')->on('core_suppliers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markups_supplier');
    }
}
