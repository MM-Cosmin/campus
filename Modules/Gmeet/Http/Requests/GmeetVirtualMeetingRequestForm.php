<?php

namespace Modules\Gmeet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GmeetVirtualMeetingRequestForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $settings = gMainSettings();
        $is_use_api = $settings->use_api;
        return [
            'participate_ids' => ['required', 'array'],
            'member_type' => ['required'],
            'topic' => ['required', 'string'],
            'gmeet_url' => ['sometimes', 'nullable', 'url', Rule::requiredIf(function () use ($is_use_api) {
                return $is_use_api == 0;
            })],
            'description' => ['sometimes', 'nullable'],
            'password' => ['sometimes', 'nullable'],
            'date' => ['required', 'date'],
            'time' => ['required'],
            'duration' => ['required', 'integer'],
            'time_start_before' => ['sometimes', 'nullable'],
            'attached_file' => ['sometimes', 'nullable', 'mimes:jpeg,png,jpg,doc,docx,pdf,xls,xlsx'],
            'is_recurring' => ['sometimes', 'nullable', Rule::requiredIf(function () use ($is_use_api) {
                return $is_use_api == 1;
            })],
            'visibility' => ['sometimes', 'string'],
            'recurring_type' => ['required_if:is_recurring,1'],
            'recurring_repeat_day' => ['required_if:is_recurring,1'],
            'recurring_end_date' => ['required_if:is_recurring,1'],
            'days' => ['required_if:recurring_type,2'],
        ];

    }
    public function attributes()
    {
        return [
            'participate_ids'=>'participants'
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
