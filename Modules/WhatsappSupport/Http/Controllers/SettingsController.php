<?php

namespace Modules\WhatsappSupport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Modules\WhatsappSupport\Entities\Settings;
use Modules\WhatsappSupport\Traits\ImageStore;

class SettingsController extends Controller
{
    use ImageStore;
    public function index()
    {
        try {
            $whatappSupportSettings = Settings::first();
            return view('whatsappsupport::settings.index', compact('whatappSupportSettings'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'primary_number' => "regex:/\+8801\d{1,9}/",
        ]);
        if ($validator->fails()) {
            Toastr::error('Invalid Number Format', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/whatsapp_support/';
            $setting = Settings::find($request->id);
            if ($request->has('color')) {
                $setting->color = $request->color;
                $setting->intro_text = $request->intro_text;
                $setting->welcome_message = $request->welcome_message;
            }
            if ($request->has('agent_type')) {
                $setting->agent_type = $request->agent_type;
                $setting->availability = $request->availability;
                $setting->showing_page = $request->showing_page;
                $setting->open_popup = $request->open_popup;
                $setting->show_unavailable_agent = $request->show_unavailable_agent;
                $setting->disable_for_admin_panel = $request->disable_for_admin_panel;
                $setting->homepage_url = $request->homepage_url;
                $setting->primary_number = $request->primary_number;
                $setting->bubble_logo = fileUpdate($setting->bubble_logo, $request->bubble_logo, $destination);
            }
            if ($request->has('layout')) {
                $setting->layout = $request->layout;
            }
            $setting->save();
            Toastr::success('Settings Updated', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
