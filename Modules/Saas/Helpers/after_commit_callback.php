<?php

use App\SmChartOfAccount;
use Illuminate\Support\Facades\Auth;

if(!function_exists('after_commit_callback')){
    function after_commit_callback($school, $table){
        $callback_function = 'after_commit_'.$table;
        if(function_exists($callback_function)){
            $data = \Illuminate\Support\Facades\DB::table($table)->where('school_id', $school->id);
            $callback_function($school, $data);
        }
    }
}

if(!function_exists('after_commit_sm_general_settings')){
    function after_commit_sm_general_settings($school, $data){

        $default_settings = \App\SmGeneralSettings::withOutGlobalScopes()->where('school_id', 1)->first();
        $default_week_start_day = \App\SmWeekend::withOutGlobalScopes()->where('school_id', 1)->where('id', $default_settings ? $default_settings->week_start_id : 3 )->first();
        $week_start_day = \App\SmWeekend::withOutGlobalScopes()->where('school_id', $school->id)->where('name', $default_week_start_day ? $default_week_start_day->name : 'Monday')->first();

        $default_income_head = SmChartOfAccount::withOutGlobalScopes()->where('active_status', '=', 1)
            ->where('school_id', 1)
            ->where('id', $default_settings ? $default_settings->income_head_id : 1)
            ->where('type', 'I')->first();

        $income_head = SmChartOfAccount::withOutGlobalScopes()->where('active_status', '=', 1)
            ->where('school_id', $school->id)
            ->where('head', $default_income_head ? $default_income_head->head : 'Fees Collection')
            ->where('type', 'I')->first();

        $default_lang = \App\SmLanguage::where('school_id', 1)->where('active_status', 1)->first();

        $language = \App\SmLanguage::where('language_universal', $default_lang ? $default_lang->language_universal : 'en')->where('school_id', $school->id)->first();

        $data->update([
            'school_name' => $school->school_name,
            'site_title' => $school->school_name,
            'email' => $school->email,
            'address' => $school->address ?? $default_settings->address,
            'school_code' => $school->school_code ?? $default_settings->school_code,
            'phone' => $school->phone ?? $default_settings->phone,
            'week_start_id' => $week_start_day->id,
            'income_head_id' => $income_head->id,
            'language_id' => $language ? $language->id : null
        ]);
    }
}
