<?php

namespace Modules\Zoom\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Zoom\Entities\VirtualClass;
use Illuminate\Foundation\Http\FormRequest;

class VirtualClassRequest extends FormRequest
{
    /**1
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [          
            'topic' => 'required',
            'description' => 'nullable',
            'password' => 'required',
            'attached_file' => 'nullable|mimes:jpeg,png,jpg,doc,docx,pdf,xls,xlsx',
            'time' => 'required',
            'duration' => 'required',
            'join_before_host' => 'required',
            'host_video' => 'required',
            'participant_video' => 'required',
            'mute_upon_entry' => 'required',
            'waiting_room' => 'required',
            'audio' => 'required',
            'auto_recording' => 'nullable',
            'approval_type' => 'required',
            'is_recurring' => 'required',
            'recurring_type' => 'required_if:is_recurring,1',
            'recurring_repect_day' => 'required_if:is_recurring,1',
            'recurring_end_date' => 'required_if:is_recurring,1',
            'days' => 'required_if:recurring_type,2',
        ];
        if (moduleStatusCheck('University')) {
            $rules += [
                'un_session_id' => ['required'],
                'un_department_id' => ['required'],
                'un_academic_id' => ['required'],
                'un_semester_id' => ['required'],
                'un_semester_label_id' => ['required']
            ];
        } else {
            $rules += [
                'class' => ['required']
            ];
        }
        if(auth()->user()->role_id==1) {
            $rules['teacher_ids'] = ['required'];
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'teacher_ids'=>'Teacher'
        ];
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
