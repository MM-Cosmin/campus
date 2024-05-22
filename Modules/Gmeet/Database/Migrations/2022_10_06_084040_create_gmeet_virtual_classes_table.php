<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmeetVirtualClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmeet_virtual_classes', function (Blueprint $table) {
            $table->id();
            $table->string('meeting_id')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();

            $table->string('class_id')->nullable();
            $table->string('section_id')->nullable();

            $table->integer('course_id')->nullable();
            $table->integer('chapter_id')->nullable();
            $table->integer('lesson_id')->nullable();
            // google meet calendar
            $table->text('gmeet_url')->nullable();
            $table->string('event_id')->nullable();
            $table->string('recurring_event_id')->nullable();
            $table->text('calendar_link')->nullable();
            $table->string('calendar_status')->nullable();
            $table->string('visibility')->nullable()->default('private');
            //basic
            $table->string('topic')->nullable();
            $table->string('description')->nullable();
            $table->string('attached_file')->nullable();
            $table->string('date_of_meeting')->nullable();
            $table->string('time_of_meeting')->nullable();
            $table->string('meeting_duration')->nullable();
            $table->integer('time_before_start')->nullable();

            //recurring
            $table->boolean('is_recurring')->nullable();
            $table->tinyInteger('recurring_type')->nullable();
            $table->tinyInteger('recurring_repeat_day')->nullable();
            $table->string('weekly_days')->nullable();
            $table->string('recurring_end_date')->nullable();

            $table->tinyInteger('status')->default(1);
            $table->text('local_video')->nullable();
            $table->text('video_link')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
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
        Schema::dropIfExists('gmeet_virtual_classes');
    }
}
