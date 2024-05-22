<?php

use App\SmGeneralSettings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class CreateSmStudentRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('sm_student_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->integer('class_id')->nullable();
            // $table->foreign('class_id')->references('id')->on('sm_classes')->onDelete('cascade');
            $table->integer('section_id')->nullable();
            // $table->foreign('section_id')->references('id')->on('sm_sections')->onDelete('cascade');
            $table->date('date_of_birth')->nullable();
            $table->string('age')->nullable();
            $table->integer('academic_year')->nullable();

            $table->integer('gender_id')->nullable();
            // $table->foreign('gender_id')->references('id')->on('sm_base_setups')->onDelete('cascade');

            $table->string('student_email')->nullable();
            $table->string('student_mobile')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_mobile')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_relation')->nullable()->comment('F father, M mother, O other');
            $table->text('how_do_know_us')->nullable();
            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->integer('academic_id')->nullable()->default(1)->unsigned();

            $table->integer('un_session_id')->nullable();
            $table->integer('un_faculty_id')->nullable();
            $table->integer('un_department_id')->nullable();
            $table->integer('un_academic_id')->nullable();
            $table->integer('un_semester_id')->nullable();
            $table->integer('un_semester_label_id')->nullable();
            $table->integer('un_section_id')->nullable();

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
        Schema::dropIfExists('sm_student_registrations');
    }
}
