<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInAppLiveClassMeetingsTable extends Migration
{
    public function up()
    {
        Schema::create('in_app_live_class_meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id')->nullable();
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('meeting_id')->nullable()->default(1);
            $table->integer('instructor_id')->nullable()->default(1);
            $table->text('topic')->nullable()->default(null);
            $table->text('description')->nullable();
            $table->text('date')->nullable()->default(null);
            $table->text('time')->nullable()->default(null);
            $table->text('datetime')->nullable()->default(null);
            $table->text('end_at')->nullable()->default(null);
            $table->integer('duration')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('in_app_live_class_meetings');
    }
}
