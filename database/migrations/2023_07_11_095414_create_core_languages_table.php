<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_languages', function (Blueprint $table) {
            $table->id();
            $table->string("language_code")->unique()->comment("language code");
            $table->string("language_name")->comment("language name");
            $table->set("language_type",['LTR','RTL'])->default("LTR")->comment("language type i.e LTR(Left To Right), RTL(Right To Left)");
            $table->tinyInteger("status")->default(1);
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
        Schema::dropIfExists('core_languages');
    }
}
