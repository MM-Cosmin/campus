<?php

namespace Modules\Gmeet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentRequestForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type'=>['required', 'in:class,meeting'],
            'id'=>['required', 'integer', 'not_in:0'],
            'local_video'=>['required', 'mimes:mp4,ogx,oga,ogv,ogg,webm']
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
