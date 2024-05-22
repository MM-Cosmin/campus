<?php

use Modules\Gmeet\Entities\GmeetSettings;

if (!function_exists('gUserSettings')) {
    function gUserSettings(int $school_id = null, int $user_id = null)
    {
        $settings = null;
        $school_id = $school_id ?? auth()->user()->school_id;
        $user_id = $user_id ?? auth()->user()->id;

        if (!$settings) {
            $settings = gMainSettings();
            if($settings->individual_login ==1) {
                $settings = GmeetSettings::where('user_id', $user_id)->first();
                if(!$settings) {
                    GmeetSettings::updateOrCreate([
                            'user_id'=>$user_id,
                            'school_id'=>$school_id                            
                        ]);
                }
            }
        }
        return $settings;
    }
}
if (!function_exists('gMainSettings')) {
    function gMainSettings()
    {
        $gMainSettings = GmeetSettings::where('is_main', 1)->first();
        return $gMainSettings;
    }
}
if (!function_exists('hasKeySecret')) {
    function hasKeySecret()
    {
        $settings = gUserSettings();
        if(in_array(auth()->user()->role_id, [2,3])) {
            return false;
        }
        if (gMainSettings()->use_api == 1 && ($settings->api_key && $settings->api_secret)) {
            return true;
        }
        return false;
    }
}
if (!function_exists('gmeetSetup')) {
    function gmeetSetup(int $school_id = null, int $user_id = null)
    {
        $school_id = $school_id ?? auth()->user()->school_id;
        $user_id = $user_id ?? auth()->user()->id;

        $settings = gUserSettings($school_id, $user_id);
       
        if (gMainSettings()->use_api == 1 && (!$settings->api_key || !$settings->api_secret)) {
            return redirect()->route('g-meet.settings.index')->send();
        }
    }
}
