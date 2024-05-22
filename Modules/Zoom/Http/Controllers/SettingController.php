<?php

namespace Modules\Zoom\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Zoom\Entities\ZoomSetting;
use Illuminate\Support\Facades\Artisan;
use Modules\Zoom\Http\Requests\ZoomSettingRequestForm;

class SettingController extends Controller
{
    public function settings()
    {
       
        try {
            $data['setting'] = ZoomSetting::where('school_id', auth()->user()->school_id)->first();
            if(!$data['setting']){
                $s = new ZoomSetting();
                $s->package_id = 1;
                $s->school_id = auth()->user()->school_id;
                $s->save();
            }
            return view('zoom::settings', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    public function updateSettings(ZoomSettingRequestForm $request)
    {
       $status = $request->api_use_for=='on' ? 1 : 0;
        try {
            $settings =  ZoomSetting::where('school_id', auth()->user()->school_id)->first();
            $settings->update([
                'package_id' => $request['package_id'],
                'host_video' => $request['host_video'],
                'participant_video' => $request['participant_video'],
                'join_before_host' => $request['join_before_host'],
                'audio' => $request['audio'],
                'auto_recording' => $request['auto_recording'],
                'approval_type' => $request['approval_type'],
                'mute_upon_entry' => $request['mute_upon_entry'],
                'waiting_room' => $request['waiting_room'],
                'api_use_for' => $status,
                'api_key' => $request['api_key'],
                'secret_key' => $request['secret_key'],
                'account_id' => $request['account_id']
            ]); 
            Artisan::call('config:clear');
            Toastr::success('Zoom Setting updated successfully !', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }
    public function updateIndSettings(Request $request){
        // return $request->all();
        $request->validate([
            'secret_key' => 'required',
            'api_key' => 'required',
            'account_id'=>'required'
        ]);
      $settings =  User::find(Auth::user()->id);
      $settings->zoom_api_key_of_user= $request['api_key'];
      $settings->zoom_api_serect_of_user=$request['secret_key'];
      $settings->zoom_account_id=$request['secret_key'];
      $settings->save();
        return redirect()->back();
    }
}
