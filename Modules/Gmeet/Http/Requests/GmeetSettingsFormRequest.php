<?php

namespace Modules\Gmeet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GmeetSettingsFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type'=>['required', 'string'],

            'use_api'=>['sometimes', 'nullable', 'integer', 'required_if:type,api_use'],

            'api_key'=>['sometimes', 'nullable', 'required_if:type,api'],
            'api_secret_key'=>['sometimes', 'nullable','required_if:type,api'],

            'email_notification'=>['sometimes', 'nullable', 'integer', 'required_if:type,reminder'],
            'popup_notification'=>['sometimes', 'nullable', 'integer', 'required_if:type,reminder'],
           
            'individual_login'=>['sometimes', 'nullable', 'required_if:type,permission'],

        ];
    }
    
    public function attributes()
    {
        return [
            'api_key.required_if' => "when Calender Api Enable ,Api key field Can't be Empty",
            'api_secret_key.required_if' => "when Calender Api Enable ,Api Secret key field Can't be Empty",
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
