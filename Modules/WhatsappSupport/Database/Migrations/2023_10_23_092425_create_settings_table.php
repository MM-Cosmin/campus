<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('agent_type')->default('single');
            $table->string('availability')->default('both');
            $table->string('showing_page')->default('all');
            $table->string('color')->default('#0dc152');
            $table->text('intro_text')->nullable();
            $table->text('welcome_message')->nullable();
            $table->text('homepage_url');
            $table->string('primary_number');
            $table->boolean('open_popup')->default(false);
            $table->boolean('disable_for_admin_panel')->default(false);
            $table->boolean('show_unavailable_agent')->default(true);
            $table->integer('layout')->default(1);
            $table->string('bubble_logo')->nullable();
            $table->string('layout_preview_url')->default('whatsapp-support/preview-1.png');
            $table->timestamps();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });
        DB::table('settings')->insert([
            'intro_text' => 'Our customer support team is here to answer your questions. Ask us anything!',
            'welcome_message' => 'Hi, How can I help?',
            'homepage_url' => url('/'),
            'primary_number' => '+8801841136251'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
