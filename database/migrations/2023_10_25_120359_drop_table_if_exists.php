<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTableIfExists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tblHardikCustomers');
        Schema::dropIfExists('tblShyamCustomers');
        Schema::dropIfExists('tblSolankiCustomers');
        Schema::dropIfExists('tblVaishaliCustomers');
        Schema::dropIfExists('tblVijayCustomers');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
