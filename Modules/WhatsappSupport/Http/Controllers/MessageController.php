<?php

namespace Modules\WhatsappSupport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\WhatsappSupport\Entities\Message;
use Modules\WhatsappSupport\Entities\Settings;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        try {
            Message::create([
                'ip' => $request->ip(),
                'browser' => $request->browser,
                'os' => $request->os,
                'device_type' => $request->device_type,
                'message' => $request->message,
                'number' => $request->number ?? null,
            ]);
            $ws_setting = Settings::first();
            $to_number = $ws_setting->isMulti() ? $request->agent_number : $ws_setting->primary_number;
            return redirect()->to('https://api.whatsapp.com/send?phone=' . $to_number . '&text=' . $request->message);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function analytics()
    {
        try {
            $messages = Message::all();
            return view('whatsappsupport::analytics', compact('messages'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
