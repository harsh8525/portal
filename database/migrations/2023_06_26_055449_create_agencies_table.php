<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->bigIncrements("id")->comment("agency increment id");
            $table->integer("core_agency_type_id")->comment("core_agency_type id referency key");
            $table->integer("parent_id")->default(0)->comment("parent agency id");
            $table->string("agency_id")->unique()->comment("unique agency id i.e TP-54467 where TP is first character of two word, if single word than first two character from site name, and than after 5 random number with unique value");
            $table->integer("core_supplier_id")->nullable()->comment("core_suppliers id referency key");
            $table->string("full_name")->comment("agency full name");
            $table->string("short_name")->nullable()->comment("agency short name");
            $table->string("contact_person_name")->comment("agency contact person name");
            $table->string("designation")->comment("agency contact person designation");
            $table->string("license_number")->nullable()->comment("agency license number");
            $table->string("phone_no")->comment("agency phone number");
            $table->string("fax_no")->nullable()->comment("agency fax number");
            $table->string("email")->unique()->comment("unique agency email address");
            $table->string("logo")->nullable()->comment("agency logo image name");
            $table->string("web_link")->nullable()->comment("agency website link");
            $table->tinyInteger("is_stop_buy")->nullable()->default(0)->comment("is stop buy flag");
            $table->tinyInteger("is_search_only")->nullable()->default(0)->comment("is search only flag");
            $table->tinyInteger("is_cancel_right")->nullable()->default(0)->comment("is cancel rights flag");
            $table->set("status", ['active','inactive','terminated'])->default('active')->comment("agency status only active agency user can login");
            
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
        Schema::dropIfExists('agencies');
    }
}
