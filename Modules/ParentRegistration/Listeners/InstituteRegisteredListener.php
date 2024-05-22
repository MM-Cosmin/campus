<?php

namespace Modules\ParentRegistration\Listeners;

use App\Models\SmStaffRegistrationField;
use App\Models\SmStudentRegistrationField;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class InstituteRegisteredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        return;
        $school = $event->institute;
        $this->StudentField($school);
    }


    public function StudentField($school)
    {

        $request_fields = [
            'session', 'class', 'section', 'first_name', 'last_name', 'email_address', 'gender', 'date_of_birth', 'age', 'blood_group', 'religion', 'caste', 'phone_number', 'student_category_id', 'student_group_id', 'height', 'weight', 'photo', 'fathers_name', 'fathers_occupation', 'fathers_phone', 'fathers_photo', 'mothers_name', 'mothers_occupation', 'mothers_phone', 'mothers_photo', 'guardians_name', 'relation', 'guardians_email', 'guardians_photo', 'guardians_phone', 'guardians_occupation', 'guardians_address', 'current_address', 'permanent_address', 'route', 'vehicle', 'dormitory_name', 'room_number', 'national_id_number', 'local_id_number', 'bank_account_number', 'bank_name', 'previous_school_details', 'additional_notes', 'ifsc_code', 'document_file_1', 'document_file_2', 'document_file_3', 'document_file_4', 'custom_field', 'id_number'];

        if (moduleStatusCheck('Lead')) {
            $request_fields[] = 'lead_city';
            $request_fields[] = 'source_id';
        }

        $required = ['session', 'class', 'first_name', 'last_name', 'phone_number', 'relation', 'guardians_phone'];

        $fields = [];

        foreach ($request_fields as $key => $value) {
            $fields[$key] = [
                'position' => $key + 1,
                'school_id' => $school->id,
                'field_name' => $value,
                'is_required' => in_array($value, $required) ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('sm_student_fields')->insert($fields);


    }
}
