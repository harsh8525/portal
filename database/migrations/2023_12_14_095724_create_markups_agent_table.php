<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkupsAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markups_agent', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('markups_id')->nullable()->comment('reference id key of markups table');
            $table->bigInteger('agency_id')->nullable()->comment('reference id key of agencies table')->unsigned();
            $table->uuid('agent_group_id')->nullable()->comment('reference id key of agent group table');
            $table->timestamps();

            $table->foreign('markups_id')->references('id')->on('markups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('agency_id')
                ->references('id')
                ->on('agencies')
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
        Schema::dropIfExists('markups_agent');
    }
}
