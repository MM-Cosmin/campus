<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\AiContent\Entities\AiTemplateContent;

class CreateAiTemplateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_template_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('template_id')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });
        $messages = [
            1 => 'Write a content on about page',
            2 => 'Write a content on book',
            3 => 'Write a content on different content types',
            4 => 'Write a content on contact page',
            5 => 'Write a content on course',
            6 => 'Write a course heading',
            7 => 'Write a course details',
            8 => 'Write an email template',
            9 => 'Write a content on exam result',
            10 => 'Write a homework description',
            11 => 'Write a content on home page',
            12 => 'Write a news/blog',
            13 => 'Write a news heading',
            14 => 'Write a notice',
            15 => 'Write a content on notification',
            16 => 'Write a content on online exam',
            17 => 'Write a content on different pages',
            18 => 'Write a content on question bank',
            19 => 'Write an email/sms',
            20 => 'Write a sms template',
            21 => 'Write a testimonial',
            22 => 'Write a content',
            23 => 'Write a video description',

        ];
        foreach ($messages as $key => $message) {
            AiTemplateContent::create([
                'template_id' => $key,
                'content' => $message
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_template_contents');
    }
}
