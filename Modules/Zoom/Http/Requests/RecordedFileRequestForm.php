<?php

namespace Modules\Zoom\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordedFileRequestForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       
        return [
            'vedio'=>['sometimes', 'nullable', 'mimes: WEBM,MPG,MP2,MPEG,MPE,MPV,OGG,MP4, M4P, M4V,AVI,WMV,MOV, QT,FLV, SWF,AVCHD'],
            'link'=>['sometimes', 'nullable']
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
