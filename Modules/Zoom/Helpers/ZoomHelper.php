<?php

use Modules\Zoom\Entities\ZoomSetting;

if(!function_exists('zoomSettings')) {
    function zoomSettings()
    {
        $user = auth()->user();
        $settings = ZoomSetting::where('school_id', $user->school_id)->first();     
        if ($user->role_id == 4) {
            if ($settings->api_use_for == 1 && $user->zoom_api_key_of_user == null && $user->zoom_api_serect_of_user == null) {

               return false;
            }
        }
        return true;
    }
}