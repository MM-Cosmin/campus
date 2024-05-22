<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBbbMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbb_meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('instructor_id')->nullable()->default(1);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('class_id')->nullable();
            $table->text('meeting_id')->nullable()->default(null);
            $table->text('topic')->nullable()->default(null);
            $table->text('description')->nullable();
            $table->text('attendee_password')->nullable()->default(null);
            $table->text('moderator_password')->nullable()->default(null);
            $table->text('date')->nullable()->default(null);
            $table->text('time')->nullable()->default(null);
            $table->text('datetime')->nullable()->default(null);
            $table->text('time_start_before')->nullable()->default(null);
            $table->text('welcome_message')->nullable()->default(null);
            $table->text('dial_number')->nullable();
            $table->integer('max_participants')->nullable()->default(0)->comment('0 means unlimited');
            $table->text('logout_url')->nullable()->comment('The URL that the BigBlueButton client will go to after users click the OK button.');
            $table->boolean('record')->nullable()->default(false);
            $table->integer('duration')->nullable()->default(0)->comment('0 means unlimited');
            $table->boolean('is_breakout')->nullable()->default(false);
            $table->text('moderator_only_message')->nullable();
            $table->boolean('auto_start_recording')->nullable()->default(0);
            $table->boolean('allow_start_stop_recording')->nullable()->default(1);
            $table->boolean('webcams_only_ror_moderator')->nullable()->default(0);
            $table->text('logo')->default("")->nullable();
            $table->text('copyright')->default("")->nullable();
            $table->boolean('mute_on_start')->nullable()->default(false);
            $table->boolean('webcams_only_for_moderator')->nullable()->default(false);
            $table->boolean('lock_settings_disable_cam')->nullable()->default(false);
            $table->boolean('lock_settings_disable_mic')->nullable()->default(false);
            $table->boolean('lock_settings_lock_on_join')->nullable()->default(false);
            $table->boolean('lock_settings_lock_on_join_configurable')->nullable()->default(false);
            $table->boolean('join_via_html5')->nullable()->default(true);
            $table->boolean('lock_settings_disable_private_chat')->nullable()->default(false);
            $table->boolean('lock_settings_disable_public_chat')->nullable()->default(false);
            $table->boolean('lock_settings_disable_note')->nullable()->default(false);
            $table->boolean('lock_settings_locked_layout')->nullable()->default(false);
            $table->boolean('lock_settings_lock_on_oin')->nullable()->default(false);
            $table->boolean('lock_settings_sock_on_join_configurable')->nullable()->default(false);
            $table->enum('guest_policy', ['ALWAYS_ACCEPT', 'ALWAYS_DENY', 'ASK_MODERATOR'])->nullable()->default('ALWAYS_ACCEPT');
            $table->boolean('redirect')->nullable()->default(true);
            $table->boolean('join_via_html_5')->nullable()->default(true);
            $table->enum('state', ['any', 'published', 'unpublished'])->nullable()->default('any');
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
        Schema::dropIfExists('bbb_meetings');
    }
}
