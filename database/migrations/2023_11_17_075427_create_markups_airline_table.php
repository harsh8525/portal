<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkupsAirlineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('airlines', function (Blueprint $table) {
            $table->index('airline_code');
        });

        Schema::create('markups_airline', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('markups_id')->nullable()->comment('reference id key of markups table');
            $table->uuid('airline_id')->nullable()->comment('reference id key of airline table');
            $table->string('airline_code')->nullable()->comment('reference airline code key of airline table');
            $table->timestamps();

            $table->foreign('markups_id')->references('id')->on('markups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('airline_code')->references('airline_code')->on('airlines')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markups_airline');
    }
}
