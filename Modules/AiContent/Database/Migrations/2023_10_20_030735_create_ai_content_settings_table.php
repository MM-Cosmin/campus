<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\AiContent\Entities\AiContentSetting;

class CreateAiContentSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_content_settings', function (Blueprint $table) {
            $table->id();
            $table->string('ai_default_model')->nullable();
            $table->string('ai_default_language')->nullable();
            $table->string('ai_default_tone')->nullable();
            $table->string('ai_max_result_length')->nullable();
            $table->string('ai_default_creativity')->nullable();
            $table->string('open_ai_secret_key')->nullable();
            $table->timestamps();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });
        AiContentSetting::create([
            'ai_default_model' => 'text-davinci-001',
            'ai_default_language' => 'en',
            'ai_default_tone' => 'professional',
            'ai_max_result_length' => '200',
            'ai_default_creativity' => '0.5',
            'open_ai_secret_key' => '',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_content_settings');
    }
}
