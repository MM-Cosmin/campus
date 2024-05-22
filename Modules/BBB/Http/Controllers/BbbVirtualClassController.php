<?php

namespace Modules\BBB\Http\Controllers;

use App\User;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use Carbon\Carbon;
use App\SmClassSection;
use App\SmNotification;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\BBB\Entities\BbbSetting;
use Illuminate\Support\Facades\Artisan;
use Modules\BBB\Entities\BbbVirtualClass;
use Illuminate\Contracts\Support\Renderable;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

class BbbVirtualClassController extends Controller
{
    public function __construct()
    {
          Artisan::call('config:clear');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {   

        try {
            $data['setting'] = BbbSetting::first();          
            $data['user'] = Auth::user();
            $data['records'] = [];
            if ($data['user']->role_id == 1 || $data['user']->role_id == 5) {
                $data['teachers'] = SmStaff::where('active_status', 1)->where(function($q)  {
	                $q->where('role_id', 4)->orWhere('previous_role_id', 4);
                })->where('school_id', Auth::user()->school_id)->select('id', 'full_name', 'user_id')->get();

            } elseif ($data['user']->role_id == 4) {
                $teacher_info = SmStaff::where('user_id', Auth::user()->id)->where(function($q)  {
	                $q->where('role_id', 4)->orWhere('previous_role_id', 4);
                })->first(['id', 'user_id']);

                $class_ids = SmAssignSubject::where('teacher_id', $teacher_info->id)->get(['class_id']);

                $section_s = SmAssignSubject::whereIn('class_id', $class_ids)->get('section_id');

                $teachers = SmAssignSubject::whereIn('section_id', $section_s)->get('teacher_id');

                $data['teachers'] = SmStaff::where('active_status', 1)->whereIn('id', $teachers)->where('user_id', '!=', Auth::user()->id)->where(function($q)  {
                    $q->where('role_id', 4)->orWhere('previous_role_id', 4);
                })->where('school_id', Auth::user()->school_id)->select('id', 'full_name', 'user_id')->get();

            }

            $data['instructors'] = User::select('id', 'full_name')->where('role_id', 4)->get();
            // $data['classes'] = VirtualClass::select('id', 'title')->where('host','BBB')->where('type',1)->latest()->get();
            $data['classes'] = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            $data['default_settings'] = BbbSetting::first()->makeHidden('security_salt', 'server_base_url', 'created_at', 'updated_at');
            if (Auth::user()->role_id == 4) {
                $data['default_settings'] = BbbSetting::first();
                $st_id = SmStaff::where('user_id', Auth::user()->id)->first();
                $data['meetings'] = BbbVirtualClass::orderBy('id', 'DESC')->whereHas('teachers', function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })

                    ->get();
            } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
                $data['meetings'] = BbbVirtualClass::orderBy('id', 'DESC')->get();
            } elseif (Auth::user()->role_id == 3) {

                $parent = SmParent::where('user_id', Auth::user()->id)->first();
                $student_detail = SmStudent::where('parent_id', $parent->id)->get();
                $data = ['meetings'];
                foreach ($student_detail as $student) {

                    $data['meetings'] = BbbVirtualClass::orderBy('id', 'DESC')->orwhere('section_id', null)->get();

                }

            } elseif (Auth::user()->role_id == 2) {
                $data['records'] = StudentRecord::where('student_id', auth()->user()->student->id)        ->where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())->get();
                $student = SmStudent::where('user_id', Auth::user()->id)->with('studentRecord')->first();
                $class_id = $student->studentRecord->class_id;
                $section_id = $student->studentRecord->section_id;
                $data['meetings'] = BbbVirtualClass::orderBy('id', 'DESC')->where('class_id', $class_id)->where('section_id', $section_id)->orwhere('section_id', null)->get();
            }
            $data['env']['security_salt'] = env('BBB_SECURITY_SALT');
            $data['env']['server_base_url'] = env('BBB_SERVER_BASE_URL');
         
            return view('bbb::virtualClass.virtual_class', $data);

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('bbb::create');
    }

    public function myChild($id)
    {

        try {
            if (Auth::user()->role_id == 3) {
                $data['records'] = StudentRecord::where('student_id', $id)->where('school_id', auth()->user()->school_id)->where('academic_id', getAcademicId())->get();
                $student = SmStudent::where('id', $id)->first();
           
                return view('bbb::virtualClass.virtual_class', $data);
            }
        } catch (\Throwable $th) {
           
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {    
        if (Auth::user()->role_id == 4) {
            $count = count($request->teacher_ids);
            if ($count > 3) {
                Toastr::error('Can Not Select More Than 2 Person !', 'Failed');
                return redirect()->back();
            }
        }
        $topic = $request->get('topic');
        $class_id = $request->get('class_id');
        $section_id = $request->get('section');
        $attendee_password = $request->get('attendee_password');
        $moderator_password = $request->get('moderator_password');
        $date = $request->get('date');
        $time = $request->get('time');
        $chnage_default_settings = $request->get('chnage-default-settings');
        $welcome_message = $request->get('welcome_message');
        $dial_number = $request->get('dial_number');
        $max_participants = $request->get('max_participants');
        $logout_url = $request->get('logout_url');
        $record = $request->get('record');
        // $duration = $request->get('duration');
        $duration = "240";
        $time_start_before = $request->get('time_start_before');
        $description = $request->get('description');
        $is_breakout = $request->get('is_breakout');
        $moderator_only_message = $request->get('moderator_only_message');
        $auto_start_recording = $request->get('auto_start_recording');
        $allow_start_stop_recording = $request->get('allow_start_stop_recording');
        $webcams_only_for_moderator = $request->get('webcams_only_for_moderator');
        $copyright = $request->get('copyright');
        $mute_on_start = $request->get('mute_on_start');
        $lock_settings_disable_mic = $request->get('lock_settings_disable_mic');
        $lock_settings_disable_private_chat = $request->get('lock_settings_disable_private_chat');
        $lock_settings_disable_public_chat = $request->get('lock_settings_disable_public_chat');
        $lock_settings_disable_note = $request->get('lock_settings_disable_note');
        $lock_settings_locked_layout = $request->get('lock_settings_locked_layout');
        $lock_settings_lock_on_join = $request->get('lock_settings_lock_on_join');
        $lock_settings_lock_on_join_configurable = $request->get('lock_settings_lock_on_join_configurable');
        $guest_policy = $request->get('guest_policy');
        $redirect = $request->get('redirect');
        $join_via_html5 = $request->get('join_via_html5');
        $state = $request->get('state');
        $datetime = $date . " " . $time;
        $datetime = strtotime($datetime);
        $start_date = Carbon::parse($request['date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($request['time']));

        if ($request->file('attached_file') != "") {
            $file = $request->file('attached_file');
            $fileName = $request['topic'] . time() . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/zoom-meeting/', $fileName);
            $fileName = 'public/uploads/zoom-meeting/' . $fileName;
        } else {
            $fileName = "";
        }

        if (auth()->user()->role_id == 1) {
            $request->validate([

                'class_id' => 'required',
                //  'section' => 'required',
                'teacher_ids' => 'required|array',
                'topic' => 'required',
                'attendee_password' => 'required',
                'moderator_password' => 'required',
                'date' => 'required',
                'time' => 'required',
                'duration' => 'required',

            ]);
        } else {
            $request->validate([

                'class_id' => 'required',
                //  'section' => 'required',
                'topic' => 'required',
                'attendee_password' => 'required',
                'moderator_password' => 'required',
                'date' => 'required',
                'time' => 'required',
                'duration' => 'required',
            ]);
        }

        try {

            $createMeeting = Bigbluebutton::create([
                'meetingID' => "jiliu-" . date('ymd' . rand(0, 1000)),
                'meetingName' => $topic,
                'attendeePW' => $attendee_password,
                'moderatorPW' => $moderator_password,
                'welcomeMessage' => $welcome_message,
                'dialNumber' => $dial_number,
                'maxParticipants' => $max_participants,
                'logoutUrl' => $logout_url,
                'record' =>(bool)  $record,
                'duration' => $duration,
                'description' => $description,
                'isBreakout' => $is_breakout,
                'moderatorOnlyMessage' => $moderator_only_message,
                'autoStartRecording' => (bool) $auto_start_recording,
                'allowStartStopRecording' =>(bool)  $allow_start_stop_recording,
                'webcamsOnlyForModerator' => (bool) $webcams_only_for_moderator,
                'copyright' => $copyright,
                'muteOnStart' => (bool) $mute_on_start,
                'lockSettingsDisableMic' => (bool) $lock_settings_disable_mic,
                'lockSettingsDisablePrivateChat' =>(bool)  $lock_settings_disable_private_chat,
                'lockSettingsDisablePublicChat' => (bool) $lock_settings_disable_public_chat,
                'lockSettingsDisableNote' =>(bool)  $lock_settings_disable_note,
                'lockSettingsLockedLayout' => (bool) $lock_settings_locked_layout,
                'lockSettingsLockOnJoin' => (bool) $lock_settings_lock_on_join,
                'lockSettingsLockOnJoinConfigurable' => (bool) $lock_settings_lock_on_join_configurable,
                'guestPolicy' => $guest_policy,
                'redirect' => (bool) $redirect,
                'joinViaHtml5' =>(bool)  $join_via_html5,
                'state' => $state,
            ]);
            if ($createMeeting) {
                $local_meeting = BbbVirtualClass::create([
                    'meeting_id' => $createMeeting['meetingID'],
                    'section_id' => $section_id,
                    'class_id' => $class_id,
                    'topic' => $topic,
                    'description' => $request['description'],
                    'logo' => $fileName,
                    'attendee_password' => $attendee_password,
                    'moderator_password' => $moderator_password,
                    'date' => $date,
                    'time' => $time,
                    'datetime' => $datetime,
                    'welcome_message' => $welcome_message,
                    'dial_number' => $dial_number,
                    'max_participants' => $max_participants,
                    'logout_url' => $logout_url,
                    'record' => $record,
                    'duration' => $duration,
                    'time_start_before' => $time_start_before,
                    'is_breakout' => $is_breakout,
                    'moderator_only_message' => $moderator_only_message,
                    'auto_start_recording' => $auto_start_recording,
                    'allow_start_stop_recording' => $allow_start_stop_recording,
                    'webcams_only_for_moderator' => $webcams_only_for_moderator,
                    'copyright' => $copyright,
                    'mute_on_start' => $mute_on_start,
                    'lock_settings_disable_mic' => $lock_settings_disable_mic,
                    'lock_settings_disable_private_chat' => $lock_settings_disable_private_chat,
                    'lock_settings_disable_public_chat' => $lock_settings_disable_public_chat,
                    'lock_settings_disable_note' => $lock_settings_disable_note,
                    'lock_settings_locked_layout' => $lock_settings_locked_layout,
                    'lock_settings_lock_on_join' => $lock_settings_lock_on_join,
                    'lock_settings_lock_on_join_configurable' => $lock_settings_lock_on_join_configurable,
                    'guest_policy' => $guest_policy,
                    'redirect' => $redirect,
                    'join_via_html5' => $join_via_html5,
                    'state' => $state,
                    'start_time' => Carbon::parse($start_date)->toDateTimeString(),
                    'end_time' => Carbon::parse($start_date)->addMinute($request['duration'])->toDateTimeString(),
                    'created_by' => Auth::user()->id,

                ]);
            }

            // $user = new BbbMeetingUser();
            // $user->meeting_id = $local_meeting->id;
            // $user->user_id = $instructor_id;
            // $user->moderator = 1;
            // $user->save();
            if (auth()->user()->role_id == 1 || auth()->user()->role_id == 4 || auth()->user()->role_id == 5) {
                $local_meeting->teachers()->attach($request['teacher_ids']);
            } else {
                $local_meeting->teachers()->attach(Auth::user());
            }

            if ($request->section == null) {

                $all_sections = SmClassSection::where('class_id', $request['class_id'])->get('section_id', 'class_id');
                $all_section_ids = [];
                foreach ($all_sections as $section) {
                    $all_section_ids[] = $section->section_id;
                }

            } else {
                $all_section_ids = array($request['section']);
            }

            $UserList = SmStudent::where('class_id', $request['class_id'])
                ->whereIn('section_id', $all_section_ids)
                ->where('school_id', Auth::user()->school_id)
                ->select('user_id', 'role_id', 'parent_id')->get();
            $this->setNotificaiton($UserList, $updateStatus = 0);

            DB::commit();

            if ($local_meeting) {
                $teacher_ids = $request['teacher_ids'];
                $created_person = User::find($local_meeting->created_by);

                if ($created_person->role_id == 1 || $created_person->role_id == 5) {
                    $msg = $created_person->full_name . ' created BBB Virtual Class For You!';
                } elseif ($created_person->role_id == 4) {
                    $msg = $created_person->full_name . ' Added you into BBB Virtual Class !';

                } else {
                    $msg = $created_person->full_name . ' created BBB Virtual Class For You!';
                }
                $users = User::whereIn('id', $teacher_ids)->where('role_id', 4)->where('id', '!=', Auth::user()->id)->where('school_id', 1)->get();
                foreach ($users as $user) {
                    $notification = new SmNotification();
                    $notification->message = $msg;
                    $notification->is_read = 0;
                    $notification->url = route('bbb.virtual-class');
                    $notification->user_id = $user->id;
                    $notification->role_id = $user->role_id;
                    $notification->school_id = 1;
                    $notification->academic_id = $user->academic_id;
                    $notification->date = date('Y-m-d');
                    $notification->save();
                }
            }

            if ($local_meeting) {
                Toastr::success('Virtual class created successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }

            Toastr::success('Class updated successful', 'Success');
            return redirect()->route('bbb.virtual-class');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        try {
            $localMeetingData = BbbVirtualClass::findOrFail($id);
            return view('bbb::virtualClass.virtual_class_detail', compact('localMeetingData'));

        } catch (\Exception $e) {

        }

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {

            $data['editdata'] = BbbVirtualClass::findOrFail($id);
            if (Auth::user()->role_id != 1) {
                if (Auth::user()->id != $data['editdata']->created_by) {
                    Toastr::error('Meeting is created by other, you could not Edit !', 'Failed');
                    return redirect()->back();
                }
            }
            $data['setting'] = BbbSetting::first();
            $data['user'] = Auth::user();

            $data['meetings'] = BbbVirtualClass::orderBy('id', 'DESC')->get();

            if ($data['user']->role_id == 1 || Auth::user()->role_id == 5) {
                $data['teachers'] = SmStaff::where('active_status', 1)->where(function($q)  {
	$q->where('role_id', 4)->orWhere('previous_role_id', 4);})->where('school_id', Auth::user()->school_id)->select('id', 'full_name', 'user_id')->get();

            } elseif ($data['user']->role_id == 4) {
                $teacher_info = SmStaff::where('user_id', Auth::user()->id)->where(function($q)  {
	$q->where('role_id', 4)->orWhere('previous_role_id', 4);})->first(['id', 'user_id']);

                $class_ids = SmAssignSubject::where('teacher_id', $teacher_info->id)->get(['class_id']);

                $section_s = SmAssignSubject::whereIn('class_id', $class_ids)->get('section_id');

                $teachers = SmAssignSubject::whereIn('section_id', $section_s)->get('teacher_id');

                $data['teachers'] = SmStaff::where('active_status', 1)->whereIn('id', $teachers)->where('user_id', '!=', Auth::user()->id)->where(function($q)  {
                        $q->where('role_id', 4)->orWhere('previous_role_id', 4);
                    })->where('school_id', Auth::user()->school_id)->select('id', 'full_name', 'user_id')->get();

                // $data['teachers'] = SmStaff::where('active_status', 1)->where('user_id','!=',Auth::user()->id)->where('role_id', 4)->where('school_id', Auth::user()->school_id)->select('id','full_name','user_id')->get();

            }

            $data['instructors'] = User::select('id', 'full_name')->where('role_id', 4)->get();
            // $data['classes'] = Class::select('id', 'title')->where('host','BBB')->where('type',1)->latest()->get();
            $data['classes'] = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $data['class_sections'] = SmSection::whereIn('id', $data['editdata']->class->classSections->pluck('section_id'))->get();
            $data['env']['security_salt'] = env('BBB_SECURITY_SALT');
            $data['env']['server_base_url'] = env('BBB_SERVER_BASE_URL');
            return view('bbb::virtualClass.virtual_class', $data);

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role_id == 4) {
            $count = count($request->teacher_ids);
            if ($count > 2) {
                Toastr::error('Can Not Select More Than 2 Person !', 'Failed');
                return redirect()->back();
            }
        }

        if (auth()->user()->role_id == 1) {
            $request->validate([

                'class_id' => 'required',
                //  'section' => 'required',
                'teacher_ids' => 'required|array',
                'topic' => 'required',
                'attendee_password' => 'required',
                'moderator_password' => 'required',
                'date' => 'required',
                'time' => 'required',
                'duration' => 'required',

            ]);
        } else {
            $request->validate([

                'class_id' => 'required',
                //  'section' => 'required',
                'topic' => 'required',
                'attendee_password' => 'required',
                'moderator_password' => 'required',
                'date' => 'required',
                'time' => 'required',
                'duration' => 'required',
            ]);
        }
        $instructor_id = $request->get('instructor_id');
        $class_id = $request->get('class_id');
        $topic = $request->get('topic');
        $attendee_password = $request->get('attendee_password');
        $moderator_password = $request->get('moderator_password');
        $date = $request->get('date');
        $time = $request->get('time');
        $chnage_default_settings = $request->get('chnage-default-settings');
        $welcome_message = $request->get('welcome_message');
        $dial_number = $request->get('dial_number');
        $max_participants = $request->get('max_participants');
        $logout_url = $request->get('logout_url');
        $record = $request->get('record');
        $duration = $request->get('duration');
        $time_start_before = $request->get('time_start_before');
        $description = $request->get('description');
        $is_breakout = $request->get('is_breakout');
        $moderator_only_message = $request->get('moderator_only_message');
        $auto_start_recording = $request->get('auto_start_recording');
        $allow_start_stop_recording = $request->get('allow_start_stop_recording');
        $webcams_only_for_moderator = $request->get('webcams_only_for_moderator');
        $copyright = $request->get('copyright');
        $mute_on_start = $request->get('mute_on_start');
        $lock_settings_disable_mic = $request->get('lock_settings_disable_mic');
        $lock_settings_disable_private_chat = $request->get('lock_settings_disable_private_chat');
        $lock_settings_disable_public_chat = $request->get('lock_settings_disable_public_chat');
        $lock_settings_disable_note = $request->get('lock_settings_disable_note');
        $lock_settings_locked_layout = $request->get('lock_settings_locked_layout');
        $lock_settings_lock_on_join = $request->get('lock_settings_lock_on_join');
        $lock_settings_lock_on_join_configurable = $request->get('lock_settings_lock_on_join_configurable');
        $guest_policy = $request->get('guest_policy');
        $redirect = $request->get('redirect');
        $join_via_html5 = $request->get('join_via_html5');
        $state = $request->get('state');
        $datetime = $date . " " . $time;
        $datetime = strtotime($datetime);

        // $request->validate([
        //     'topic' => 'required',
        //     'instructor_id' => 'required',
        //     'class_id' => 'required',
        //     'attendee_password' => 'required',
        //     'date' => 'required',
        //     'time' => 'required',

        // ]);

        $system_meeting = BbbVirtualClass::updateOrCreate([
            'id' => $id,
        ], [
            'topic' => $topic,
            'attendee_password' => $attendee_password,
            'moderator_password' => $moderator_password,
            'date' => $date,
            'time' => $time,
            'instructor_id' => $instructor_id,
            'class_id' => $class_id,
            'datetime' => $datetime,
            'welcome_message' => $welcome_message,
            'dial_number' => $dial_number,
            'max_participants' => $max_participants,
            'logout_url' => $logout_url,
            'record' => (bool) $record,
            'duration' => $duration,
            'time_start_before' => $time_start_before,
            'is_breakout' => (bool) $is_breakout,
            'moderator_only_message' => (bool) $moderator_only_message,
            'auto_start_recording' => (bool) $auto_start_recording,
            'allow_start_stop_recording' => (bool) $allow_start_stop_recording,
            'webcams_only_for_moderator' => (bool) $webcams_only_for_moderator,
            'copyright' => $copyright,
            'mute_on_start' => (bool) $mute_on_start,
            'lock_settings_disable_mic' => (bool) $lock_settings_disable_mic,
            'lock_settings_disable_private_chat' => (bool) $lock_settings_disable_private_chat,
            'lock_settings_disable_public_chat' => (bool) $lock_settings_disable_public_chat,
            'lock_settings_disable_note' => (bool) $lock_settings_disable_note,
            'lock_settings_locked_layout' => (bool) $lock_settings_locked_layout,
            'lock_settings_lock_on_join' => (bool) $lock_settings_lock_on_join,
            'lock_settings_lock_on_join_configurable' => (bool) $lock_settings_lock_on_join_configurable,
            'guest_policy' => $guest_policy,
            'redirect' => (bool) $redirect,
            'join_via_html5' => (bool) $join_via_html5,
            'state' => $state,

        ]);
        if ($request->file('attached_file') != "") {
            if (file_exists($system_meeting->logo)) {
                unlink($system_meeting->logo);
            }
            $file = $request->file('attached_file');
            $fileName = $request['topic'] . time() . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/zoom-meeting/', $fileName);
            $fileName = 'public/uploads/zoom-meeting/' . $fileName;
            $system_meeting->update([
                'logo' => $fileName,
            ]);
        }

        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 4 || auth()->user()->role_id == 5) {

            $system_meeting->teachers()->detach();
            $system_meeting->teachers()->attach($request['teacher_ids']);
        } else {
            $system_meeting->teachers()->detach();
            $system_meeting->teachers()->attach($request['teacher_ids']);
        }

        $UserList = SmStudent::where('class_id', $request['class'])
            ->where('section_id', $request['section'])
            ->where('school_id', Auth::user()->school_id)
            ->select('user_id', 'role_id', 'parent_id')->get();
        $this->setNotificaiton($UserList, $updateStatus = 1);

        DB::commit();
        Toastr::success('Virtual Class updated successful', 'Success');

        return redirect()->route('bbb.virtual-class');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {

        try {
            $localMeeting = BbbVirtualClass::findOrFail($id);
          


            if (Auth::user()->role_id != 1) {
                if (Auth::user()->id != $localMeeting->created_by) {
                    Toastr::error('Meeting is created by other, you could not DELETE !', 'Failed');
                    return redirect()->back();
                }
            }

            if (file_exists($localMeeting->logo)) {
                unlink($localMeeting->logo);
            }

            $localMeeting->delete();
            DB::table('bbb_virtual_class_teachers')->where('meeting_id', $id)->delete();

            Toastr::success('BBB Virtual Class deleted successful', 'Success');
            return redirect()->route('bbb.virtual-class');

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();

        }

    }

    private function setNotificaiton($users, $updateStatus)
    {
        $now = Carbon::now('utc')->toDateTimeString();
        $school_id = Auth::user()->school_id;
        $notification_datas = [];

        if ($updateStatus == 1) {
            foreach ($users as $key => $user) {
                array_push(
                    $notification_datas,
                    [
                        'user_id' => $user->user_id,
                        'role_id' => 2,
                        'school_id' => $school_id,
                        'date' => date('Y-m-d'),
                        'message' => 'Bigbluebutton virtual class room details udpated',
                        'url' => route('bbb.virtual-class'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
                array_push(
                    $notification_datas,
                    [
                        'user_id' => $user->parent_id,
                        'role_id' => 3,
                        'school_id' => $school_id,
                        'date' => date('Y-m-d'),
                        'message' => 'Bigbluebutton virtual class room details udpated of your child',
                        'url' => route('bbb.virtual-class'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            };
        } else {
            foreach ($users as $key => $user) {
                array_push(
                    $notification_datas,
                    [
                        'user_id' => $user->user_id,
                        'role_id' => 2,
                        'school_id' => $school_id,
                        'date' => date('Y-m-d'),
                        'message' => 'Bigbluebutton virtual class room created for you',
                        'url' => route('bbb.virtual-class'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
                array_push(
                    $notification_datas,
                    [
                        'user_id' => $user->parent_id,
                        'role_id' => 3,
                        'school_id' => $school_id,
                        'date' => date('Y-m-d'),
                        'message' => 'Bigbluebutton virtual class room created for your child',
                        'url' => route('bbb.virtual-class'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            };
        }
        SmNotification::insert($notification_datas);
    }

}
