<?php

namespace Modules\AppSlider\Http\Requests;

use Modules\AppSlider\Entities\AppSlider;
use Illuminate\Foundation\Http\FormRequest;

class AppSliderUpdateFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $image = 1;
        $appSlider = AppSlider::findOrFail($this->id);
        if ($appSlider) {
           $image = $appSlider->slider_image;
        }
        $rules = [
            'title' => ['required'],
            'url' => ['required', 'url'],
            'status' => ['required'],
        ];
        if ($image =null) {
            $rules =  [
                'slider_image' => ['required'],
            ];
        }

        return $rules;
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
