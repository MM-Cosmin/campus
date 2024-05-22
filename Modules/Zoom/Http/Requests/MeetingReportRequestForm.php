<?php

namespace Modules\Zoom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingReportRequestForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {       
        return [
            "member_type" => 'required',
            "teachser_ids" => 'nullable',
            "from_time" => 'nullable',
            "to_time" => 'nullable',
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
