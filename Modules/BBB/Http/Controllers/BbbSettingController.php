<?php

namespace Modules\BBB\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Modules\BBB\Entities\BbbSetting;

class BbbSettingController extends Controller
{
    public function settings()
    {
        $setting = BbbSetting::first();        
        return view('bbb::settings', compact('setting'));
    }

    public function updateSettings(Request $request)
    {
        
        $request->validate([
            'password_length' => 'required',          
            'record' => 'required',
            'duration' => 'required',
            'security_salt' => 'required',
            'server_base_url' => 'required',
        ]);

        try {
           $bbbSettings = BbbSetting::updateOrCreate([
                'id' => 1,
            ], [
                'password_length' => $request->get('password_length'),
                'welcome_message' => $request->get('welcome_message'),
                'dial_number' => $request->get('dial_number'),
                'max_participants' => $request->get('max_participants'),
                'logout_url' => $request->get('logout_url'),
                'record' => $request->get('record'),
                'duration' => $request->get('duration'),
                'is_breakout' => $request->get('is_breakout'),
                'moderator_only_message' => $request->get('moderator_only_message'),
                'auto_start_recording' => $request->get('auto_start_recording'),
                'allow_start_stop_recording' => $request->get('allow_start_stop_recording'),
                'webcams_only_for_moderator' => $request->get('webcams_only_for_moderator'),
                'copyright' => $request->get('copyright'),
                'mute_on_start' => $request->get('mute_on_start'),
                'lock_settings_disable_mic' => $request->get('lock_settings_disable_mic'),
                'lock_settings_disable_private_chat' => $request->get('lock_settings_disable_private_chat'),
                'lock_settings_disable_public_chat' => $request->get('lock_settings_disable_public_chat'),
                'lock_settings_disable_note' => $request->get('lock_settings_disable_note'),
                'lock_settings_locked_layout' => $request->get('lock_settings_locked_layout'),
                'lock_settings_lock_on_join' => $request->get('lock_settings_lock_on_join'),
                'lock_settings_lock_on_join_configurable' => $request->get('lock_settings_lock_on_join_configurable'),
                'guest_policy' => $request->get('guest_policy'),
                'redirect' => $request->get('redirect'),
                'join_via_html5' => $request->get('join_via_html5'),
                'state' => $request->get('state'),
                'security_salt'=>$request->security_salt,
                'server_base_url'=>$request->server_base_url,

            ]);
            $security_salt = $request->get('security_salt');
            $server_base_url = $request->get('server_base_url');
            
            putEnvConfigration('BBB_SECURITY_SALT',$security_salt);
            putEnvConfigration('BBB_SERVER_BASE_URL', $server_base_url);

            Artisan::call('config:clear');
            Toastr::success('BBB Setting updated successfully !', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }
}
