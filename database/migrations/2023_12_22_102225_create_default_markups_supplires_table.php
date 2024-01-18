<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultMarkupsSuppliresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_markups_suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('default_markups_id')->nullable()->comment('reference id key of default_markups table');
            $table->bigInteger('supplier_id')->nullable()->comment('reference id key of core supplier table')->unsigned();
            $table->timestamps();

            $table->foreign('default_markups_id')->references('id')->on('default_markups')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('default_markups_suppliers');
    }
}
