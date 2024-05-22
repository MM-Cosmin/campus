<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBbbMeetingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbb_meeting_users', function (Blueprint $table) {
            $table->id();
            $table->integer('meeting_id')->default(1);
            $table->integer('user_id')->default(1);
            $table->integer('moderator')->default(0)->comment('1= moderator , 0=attendee');
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
        Schema::dropIfExists('bbb_meeting_users');
    }
}
