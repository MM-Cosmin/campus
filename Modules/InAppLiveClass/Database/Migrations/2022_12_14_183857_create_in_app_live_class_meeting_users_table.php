<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInAppLiveClassMeetingUsersTable extends Migration
{
    public function up()
    {
        Schema::create('in_app_live_class_meeting_users', function (Blueprint $table) {
            $table->id();
            $table->integer('meeting_id')->default(1);
            $table->integer('user_id')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('in_app_live_class_meeting_users');
    }
}
