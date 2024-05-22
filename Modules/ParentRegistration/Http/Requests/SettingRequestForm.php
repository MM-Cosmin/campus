<?php

namespace Modules\ParentRegistration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequestForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date'=>['sometimes', 'nullable'],
            'end_date'=>['sometimes', 'nullable', 'after_or_equal:start_date']
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
