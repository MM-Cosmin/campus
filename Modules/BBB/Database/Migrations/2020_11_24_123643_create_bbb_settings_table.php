<?php
use Modules\BBB\Entities\BbbSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBbbSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbb_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('password_length')->default(6);
            $table->text('welcome_message')->nullable()->default(null);
            $table->text('dial_number')->nullable();
            $table->integer('max_participants')->default(0)->comment('0 means unlimited');
            $table->text('logout_url')->nullable()->comment('The URL that the BigBlueButton client will go to after users click the OK button.');
            $table->boolean('record')->default(false);
            $table->integer('duration')->default(0)->comment('0 means unlimited');
            $table->boolean('is_breakout')->default(false);
            $table->text('moderator_only_message')->nullable();
            $table->boolean('auto_start_recording')->default(0);
            $table->boolean('allow_start_stop_recording')->default(1);
            $table->boolean('webcams_only_ror_moderator')->default(0);
            $table->text('copyright')->default("");
            $table->boolean('mute_on_start')->default(false);
            $table->boolean('webcams_only_for_moderator')->default(false);
            $table->boolean('lock_settings_disable_cam')->default(false);
            $table->boolean('lock_settings_disable_mic')->default(false);
            $table->boolean('lock_settings_lock_on_join')->default(false);
            $table->boolean('lock_settings_lock_on_join_configurable')->default(false);
            $table->boolean('join_via_html5')->default(true);
            $table->boolean('lock_settings_disable_private_chat')->default(false);
            $table->boolean('lock_settings_disable_public_chat')->default(false);
            $table->boolean('lock_settings_disable_note')->default(false);
            $table->boolean('lock_settings_locked_layout')->default(false);
            $table->boolean('lock_settings_lock_on_oin')->default(false);
            $table->boolean('lock_settings_sock_on_join_configurable')->default(false);
            $table->enum('guest_policy',['ALWAYS_ACCEPT','ALWAYS_DENY','ASK_MODERATOR'])->default('ALWAYS_ACCEPT');
            $table->boolean('redirect')->default(true);
            $table->boolean('join_via_html_5')->default(true);
            $table->enum('state',['any','published','unpublished'])->default('any');
            $table->text('security_salt')->nullable();
            $table->text('server_base_url')->nullable();
            $table->timestamps();
        });

        $s = new BbbSetting();
        $s->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bbb_settings');
    }
}
