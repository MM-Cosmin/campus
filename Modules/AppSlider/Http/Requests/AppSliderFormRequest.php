<?php

namespace Modules\AppSlider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppSliderFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required'],
            'url' => ['required', 'url'],
            'slider_image' => ['required'],
            'status' => ['required'],
        ];
        // 'dimensions:max_width:480,max_height:60'
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
