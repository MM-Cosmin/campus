<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAiGeneratedContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_generated_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('input_text')->nullable();
            $table->longText('output_text')->nullable();
            $table->string('model')->nullable();
            $table->integer('tokens')->nullable();
            $table->integer('template_id')->nullable();
            $table->integer('words')->nullable();
            $table->integer('temperature')->nullable();
            $table->integer('frequency_penalty')->nullable();
            $table->string('lang')->nullable();
            $table->timestamps();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_generated_contents');
    }
}
