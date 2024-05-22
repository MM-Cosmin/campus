<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\ParentRegistration\Entities\SmRegistrationSetting;

class CreateSmRegistrationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('sm_registration_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position')->default(1)->comment('1=Header, 2=Footer, 0=hide');
            $table->integer('registration_permission')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('registration_after_mail')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('approve_after_mail')->default(1)->comment('1=enable, 2=Disable');

            $table->integer('recaptcha')->default(1)->comment('1=enable, 2=Disable');
            $table->string('nocaptcha_sitekey')->nullable();
            $table->string('nocaptcha_secret')->nullable();
            // newly added
            $table->integer('academic')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('class')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('first_name')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('last_name')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('gender')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('date_of_birth')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('age')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('student_email')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('student_mobile')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('guardian_name')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('guardian_realtion')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('guardian_email')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('guardian_mobile')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('how_know')->default(1)->comment('1=enable, 2=Disable');
            $table->integer('notice_board')->default(1)->comment('1=enable, 2=Disable');
            $table->text('notice_text')->nullable();
            //end

            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            $table->integer('school_id')->nullable()->default(1)->unsigned();

            $table->integer('academic_id')->nullable()->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');

            $table->timestamps();

        });

        $setting = new SmRegistrationSetting();

        $setting->recaptcha = 2;

        $setting->save();
        $colum_names = ['academic', 'class', 'first_name', 'last_name', 'gender', 'date_of_birth', 'age', 'student_email', 'student_mobile', 'guardian_name', 'guardian_realtion', 'guardian_email', 'guardian_mobile', 'how_know', 'notice_board'];
        $colum_name2 = "notice_text";
        foreach ($colum_names as $name) {

            if (!Schema::hasColumn('sm_registration_settings', $name)) {
                Schema::table('sm_registration_settings', function ($table) use ($name) {
                    $table->integer($name)->default(1);
                });
            }
        }

        if (!Schema::hasColumn('sm_registration_settings', $colum_name2)) {
            Schema::table('sm_registration_settings', function ($table) use ($colum_name2) {
                $table->text($colum_name2)->nullable();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_student_registrations');
    }
}
