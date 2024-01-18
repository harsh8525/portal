<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->comment("customer unique email");
            $table->integer('customer_id')->comment("customer unique id");
            $table->integer('agency_id')->default(0)->comment("customer agency id");
            $table->string("first_name")->nullable()->comment("customer first name");
            $table->string("last_name")->nullable()->comment("customer last name");
            $table->string("mobile")->nullable()->comment("customer phone number");
            $table->date("date_of_birth")->nullable()->comment("customer date of birth");
            $table->string("profile_photo")->nullable()->comment("customer profile photo");
            $table->string("google_id")->nullable()->comment("customer google profile id");
            $table->string("facebook_id")->nullable()->comment("customer facebook profile id");
            $table->set('status', ['active','inactive','terminated','deleted'])->default('active');
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
        Schema::dropIfExists('customers');
    }
}
