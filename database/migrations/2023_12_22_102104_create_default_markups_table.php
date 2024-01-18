<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultMarkupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_markups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('service_type_id')->nullable()->comment('reference id key of core service types table')->unsigned();
            $table->enum('b2c_markup_type', ['percentage', 'fixed_amount'])->nullable();
            $table->decimal('b2c_markup', 10, 2)->nullable();
            $table->enum('b2b_markup_type', ['percentage', 'fixed_amount'])->nullable();
            $table->decimal('b2b_markup', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

                $table->foreign('service_type_id')
                ->references('id')
                ->on('core_service_types')
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
        Schema::dropIfExists('default_markups');
    }
}
