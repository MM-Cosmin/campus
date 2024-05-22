<?php

namespace Modules\Zoom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZoomSettingRequestForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'package_id' => 'required',
            'host_video' => 'required',
            'participant_video' => 'required',
            'join_before_host' => 'required',
            'audio' => 'required',
            'auto_recording' => 'required',
            'approval_type' => 'required',
            'mute_upon_entry' => 'required',
            'waiting_room' => 'required',
            'secret_key' => 'required',
            'api_key' => 'required',
            'account_id' => 'required',
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
