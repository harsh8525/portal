<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firm');
            $table->string('owner_name');
            $table->string('mobile')->unique();
            $table->string('password');
            $table->string('email')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('gst_certificate')->nullable();
            $table->enum('user_type',['dealer','exclusive_dealer','channel_partner','distributor','contractor'])->default('dealer');
            $table->integer('status')->comment('0 = In Active, 1 = Active, 2 = Delete');
            
            //Additional Information
            $table->integer('distributor')->nullable();
            $table->integer('ref_distributor')->nullable();
            $table->integer('ref_dealer')->nullable();
            $table->string('website')->nullable();
            $table->string('company_gst_no')->nullable();
            $table->string('company_certificate')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('shop_gst_no')->nullable();
            $table->string('working_city')->nullable();
            $table->string('working_state')->nullable();
            
            //created_at and updated_at columns
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
        Schema::dropIfExists('app_users');
    }
}
