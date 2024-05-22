<?php

namespace Modules\Saas\Http\Controllers;



use App\SmGeneralSettings;
use App\SmStyle;
use App\SmBackgroundSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class SaasBackgroundController extends Controller
{
    public function index()
    {
        if (!Schema::hasColumn('sm_background_settings', 'school_id')) {
            Schema::table('sm_background_settings', function ($table) {
                $table->integer('school_id')->default(1)->nullable();
            });
        }

        $background_settings = SmBackgroundSetting::where('school_id', Auth::user()->school_id)->get();
        // $background_settings= SmBackgroundSetting::get();
        return view('saas::systemSettings.background_setting', compact('background_settings'));
    }

    public function schoolIndex()
    {
        $background_settings = SmBackgroundSetting::where('school_id', Auth::user()->school_id)->get();
        return view('saas::systemSettings.school_background_setting', compact('background_settings'));
    }


    public function backgroundSettingsStore(Request $request)
    {
        $request->validate([
            'background_type' => 'required'
        ]);

        if ($request->background_type == 'color') {
            $request->validate([
                'color' => 'required'
            ]);
        } else {
            $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png'
            ]);
        }


        $fileName = "";
        if ($request->file('image') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('image');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $file = $request->file('image');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/backgroundImage/', $fileName);
            $fileName = 'public/uploads/backgroundImage/' . $fileName;
        }

        if ($request->style == 1) {
            $title = 'Dashboard Background';
        } else {
            $title = 'Login Background';
        }

        $background_setting = new SmBackgroundSetting();
        $background_setting->is_default = 0;
        $background_setting->title = $title;
        $background_setting->school_id = Auth::user()->school_id;
        $background_setting->type = $request->background_type;
        if ($request->background_type == 'color') {
            $background_setting->color = $request->color;
        } else {
            $background_setting->image = $fileName;
        }

        $result = $background_setting->save();
        //   return $result;


        if ($result) {
            return redirect()->back()->with('message-success', 'Operation successful');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function schoolBackgroundSettingsStore(Request $request)
    {
        $request->validate([
            'background_type' => 'required'
        ]);

        if ($request->background_type == 'color') {
            $request->validate([
                'color' => 'required'
            ]);
        } else {
            $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png'
            ]);
        }


        $fileName = "";
        if ($request->file('image') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('image');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $file = $request->file('image');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/backgroundImage/', $fileName);
            $fileName = 'public/uploads/backgroundImage/' . $fileName;
        }

        $background_setting = new SmBackgroundSetting();
        $background_setting->is_default = 0;
        $background_setting->title = "Dashboard Background";
        $background_setting->type = $request->background_type;

        $background_setting->school_id = Auth::user()->school_id;

        if ($request->background_type == 'color') {
            $background_setting->color = $request->color;
        } else {
            $background_setting->image = $fileName;
        }
        $result = $background_setting->save();


        if ($result) {
            return redirect()->back()->with('message-success', 'Operation successful');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }


    public function backgroundSettingsStatus($id)
    {

        $background = SmBackgroundSetting::find($id);


        if ($background->is_default == 1 && $background->title == "Login Background") {
            SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->update(['is_default' => 0]);
            $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
        } else if ($background->is_default == 1 && $background->title == "Dashboard Background") {
            SmBackgroundSetting::where([['is_default', 1], ['title', 'Dashboard Background']])->update(['is_default' => 0]);
            $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
        } else if ($background->is_default == 0 && $background->title == "Login Background") {
            SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->update(['is_default' => 0]);
            $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
        } else if ($background->is_default == 0 && $background->title == "Dashboard Background") {
            SmBackgroundSetting::where([['is_default', 1], ['title', 'Dashboard Background']])->update(['is_default' => 0]);
            $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
        }


        if ($result) {
            return redirect()->back()->with('message-success', 'Operation successful');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function schoolbackgroundSettingsStatus($id)
    {

        $background = SmBackgroundSetting::find($id);


        if ($background->is_default == 1 && $background->title == "Login Background") {
            SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->update(['is_default' => 0]);
            $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
        } else if ($background->is_default == 1 && $background->title == "Dashboard Background") {
            SmBackgroundSetting::where([['is_default', 1], ['title', 'Dashboard Background']])->update(['is_default' => 0]);
            $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
        } else if ($background->is_default == 0 && $background->title == "Login Background") {
            SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->update(['is_default' => 0]);
            $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
        } else if ($background->is_default == 0 && $background->title == "Dashboard Background") {
            SmBackgroundSetting::where([['is_default', 1], ['title', 'Dashboard Background']])->update(['is_default' => 0]);
            $result = SmBackgroundSetting::where('id', $id)->update(['is_default' => 1]);
        }


        if ($result) {
            return redirect()->back()->with('message-success', 'Operation successful');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }


    public function backgroundSettingsUpdate(Request $request)
    {
        $request->validate([
            'type' => 'required'
        ]);

        if ($request->type == 'color') {
            $request->validate([
                'color' => 'required'
            ]);
        } else {
            $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png'
            ]);
        }


        $fileName = "";
        if ($request->file('image') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('image');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $file = $request->file('image');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/backgroundImage/', $fileName);
            $fileName = 'public/uploads/backgroundImage/' . $fileName;
        }


        $background_setting = SmBackgroundSetting::find(1);
        $background_setting->type = $request->type;
        if ($request->type == 'color') {
            $background_setting->color = $request->color;
            $background_setting->image = '';
            if ($background_setting->image != "" && file_exists($background_setting->image)) {
                unlink($background_setting->image);
            }
        } else {
            $background_setting->color = '';
            $background_setting->image = $fileName;
        }

        $result = $background_setting->save();


        if ($result) {
            return redirect()->back()->with('message-success', 'Operation successful');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function schoolBackgroundSettingsDelete($id)
    {
        $result = SmBackgroundSetting::find($id)->delete();
        if ($result) {
            return redirect()->back()->with('message-success-delete', 'Operation successful');
        } else {
            return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
        }
    }

    public function backgroundSettingsDelete($id)
    {
        $result = SmBackgroundSetting::find($id)->delete();
        if ($result) {
            return redirect()->back()->with('message-success-delete', 'Operation successful');
        } else {
            return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
        }
    }


    public function colorTheme()
    {

        $color_styles = SmStyle::where('school_id', Auth::user()->school_id)->get();

        return view('saas::systemSettings.color_theme', compact('color_styles'));
    }

    public function colorThemeSet($id)
    {
        $background = SmStyle::find($id);

        SmStyle::where('school_id', Auth::user()->school_id)->where('is_active', 1)->update(['is_active' => 0]);

        $result = SmStyle::where('id', $id)->update(['is_active' => 1]);
        if ($result) {
            return redirect()->back()->with('message-success', 'Operation successful');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }
}
