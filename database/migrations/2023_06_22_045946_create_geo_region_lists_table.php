<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoRegionListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_region_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('region_type', '50')->nullable()->comment('region type');
            $table->string('sub_class', '50')->nullable()->comment('region type');
            $table->string('region_name')->nullable()->comment('region name');
            $table->string('region_name_long')->nullable()->comment('region long name');
            $table->bigInteger('parent_region_id')->nullable()->comment('parent region id');
            $table->string('parent_region_type', '50')->nullable()->comment('parent region type');
            $table->string('parent_region_name')->nullable()->comment('parent region name');
            $table->string('parent_region_name_long')->nullable()->comment('parent region long name');
            $table->tinyInteger('is_active')->default(1)->comment("1=active, 0=in-active");
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
        Schema::dropIfExists('geo_region_lists');
    }
}
