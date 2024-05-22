<?php

namespace Modules\AiContent\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\AiContent\Entities\AiModels;
use Modules\AiContent\Entities\AiContentSetting;

class AiSettingController extends Controller
{
    public function index()
    {
        try {
            $data['title'] = 'AI Content Settings';
            $data['ai_models'] = AiModels::OPEN_AI_MODELS;
            $data['ai_tones'] = AiModels::AI_TONES;
            $data['ai_creativity'] = AiModels::AI_CREATIVITY;
            $data['languages'] = AiModels::SUPPORTED_LANGUAGES;
            $data['settings'] = AiContentSetting::first();
            return view('aicontent::setting.index', compact('data'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        try {
            $setting = AiContentSetting::find($request->id);
            $setting->ai_default_model = $request->ai_default_model;
            $setting->ai_default_language = $request->ai_default_language;
            $setting->ai_default_tone = $request->ai_default_tone;
            $setting->ai_max_result_length = $request->ai_max_result_length;
            $setting->ai_default_creativity = $request->ai_default_creativity;
            $setting->open_ai_secret_key = $request->open_ai_secrete_key;
            $setting->save();
            Toastr::success('Settings Updated', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
