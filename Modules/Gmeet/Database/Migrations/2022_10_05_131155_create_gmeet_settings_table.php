<?php

use App\User;
use App\SmSchool;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Gmeet\Entities\GmeetSettings;
use Illuminate\Database\Migrations\Migration;

class CreateGmeetSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmeet_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->integer('email_notification')->nullable()->default(60);
            $table->integer('popup_notification')->nullable()->default(10);
            $table->tinyInteger('is_main')->nullable()->default(0);
            $table->tinyInteger('use_api')->nullable()->default(0);
            $table->integer('user_id')->nullable();
            $table->tinyInteger('individual_login')->nullable()->default(0);
            $table->integer('school_id')->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });
        $schools = SmSchool::all();
        foreach($schools as $school) {
            $user_id = User::where('school_id', $school->id)->where('role_id', 1)->value('id') ?? null;
            GmeetSettings::where('school_id', $school->id)->updateOrCreate([
                'use_api'=>0,               
                'is_main'=>1,
                'user_id'=>$user_id               
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
        Schema::dropIfExists('gmeet_settings');
    }
}
