<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\AiContent\Entities\AiTemplate;
use Illuminate\Database\Migrations\Migration;

class CreateAiTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('icon')->nullable();
            $table->integer('type')->default(1)->comment('1=Pre define, 2=user define');
            $table->integer('status')->default(1)->comment('1=Active, 2=Inactive');
            $table->integer('created_by')->nullable();
            $table->timestamps();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });
        $system_templates = [
            'About Page',
            'Book',
            'Content Type',
            'Contact Page',
            'Course',
            'Course Heading',
            'Course Details Heading',
            'Email Template',
            'Event',
            'Exam Result',
            'Homework',
            'Home Settings Page',
            'News/Blog',
            'News Heading',
            'Notice Board',
            'Notification Settings',
            'Online Exam',
            'Pages',
            'Question Bank',
            'Send Email/SMS',
            'Sms Template',
            'Testimonial',
            'Upload Content',
            'Video',
        ];
        foreach ($system_templates as $key => $template) {
            AiTemplate::create([
                'name' => $template,
                'slug' => str_replace(' ', '-', strtolower($template)),
                'icon' => 'fa fa-file',
                'type' => 1,
                'status' => 1,
                'created_by' => 1,
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
        Schema::dropIfExists('ai_templates');
    }
}
