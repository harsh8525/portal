<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTemplateI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_template_i18ns', function (Blueprint $table) {
            $table->id();
            $table->uuid('sms_id');
            $table->string('name');
            $table->string('content');
            $table->string('language_code');
            $table->timestamps();
            
            $table->foreign('sms_id')
              ->references('id')
              ->on('sms_templates');

            $table->foreign('language_code')
            ->references('language_code')
            ->on('core_languages')
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
        Schema::dropIfExists('sms_template_i18ns');
    }
}
