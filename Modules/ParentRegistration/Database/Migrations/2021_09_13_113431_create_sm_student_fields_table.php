<?php

use App\SmSchool;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Modules\ParentRegistration\Entities\SmStudentField;

class CreateSmStudentFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('sm_student_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->nullable();
            $table->string('label_name')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->tinyInteger('is_required')->default(0);
            $table->integer('position')->nullable();
            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->string('admin_section')->nullable();
            $table->timestamps();
        });

        try {
            $request_fields = [
                'session',
                'class',
                'section',
                'un_session_id',
                'un_academic_id',
                'un_faculty_id',
                'un_department_id',
                'un_semester_id',
                'un_semester_label_id',
                'un_section_id',
                'first_name',
                'last_name',
                'email_address',
                'gender',
                'date_of_birth',
                'age',
                'blood_group',
                'religion',
                'caste',
                'phone_number',
                'id_number',
                'student_category_id',
                'student_group_id',
                'height',
                'weight',
                'photo',
                'fathers_name',
                'fathers_occupation',
                'fathers_phone',
                'fathers_photo',
                'mothers_name',
                'mothers_occupation',
                'mothers_phone',
                'mothers_photo',
                'guardians_name',
                'relation',
                'guardians_email',
                'guardians_photo',
                'guardians_phone',
                'guardians_occupation',
                'guardians_address',
                'current_address',
                'permanent_address',
                'route',
                'vehicle',
                'dormitory_name',
                'room_number',
                'national_id_number',
                'local_id_number',
                'bank_account_number',
                'bank_name',
                'previous_school_details',
                'additional_notes',
                'ifsc_code',
                'document_file_1',
                'document_file_2',
                'document_file_3',
                'document_file_4',
                'custom_field',
            ];
            $schools = SmSchool::get();
            foreach ($schools as $school) {
                foreach ($request_fields as $key => $value) {
                    $check = SmStudentField::where('field_name', $value)->where('school_id', $school->id)->first();
                    $field = new SmStudentField;
                    $field->position = $key + 1;
                    $field->field_name = $value;
                    $field->school_id = $school->id;
                    if ($check) {
                        continue;
                    }
                    $field->save();
                }
                if (moduleStatusCheck('Lead') == true) {
                    $required_fields = ['session', 'class', 'first_name', 'last_name', 'relation', 'guardians_email', 'phone_number'];
                } else {
                    $required_fields = ['session', 'class', 'first_name', 'last_name', 'gender', 'date_of_birth', 'relation', 'phone_number', 'guardians_email'];
                }

                if (moduleStatusCheck('University')) {
                    $un_required_fields = [
                        'un_session_id',
                        'un_academic_id',
                        'un_faculty_id',
                        'un_department_id',
                        'un_semester_id',
                        'un_semester_label_id',
                        'un_section_id',
                        'first_name',
                        'last_name',
                        'phone_number',
                    ];
                    SmStudentField::whereIn('field_name', $un_required_fields)->where('school_id', $school->id)->update(['is_required' => 1]);
                } else {
                    SmStudentField::whereIn('field_name', $required_fields)->where('school_id', $school->id)->update(['is_required' => 1]);
                }
            }
            SmStudentField::whereIn('field_name', ['route', 'vehicle'])->update([
                'admin_section' => 'transport'
            ]);
            SmStudentField::whereIn('field_name', ['dormitory_name', 'room_number'])->update([
                'admin_section' => 'dormitory'
            ]);
            SmStudentField::whereIn('field_name', ['custom_field'])->update([
                'admin_section' => 'custom_field'
            ]);
        } catch (\Throwable$th) {
            Log::info($th);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_student_fields');
    }
}
