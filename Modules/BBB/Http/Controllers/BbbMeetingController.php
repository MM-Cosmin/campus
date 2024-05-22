<?php

namespace Modules\BBB\Http\Controllers;


use App\User;
use App\SmClass;
use App\YearCheck;
use Carbon\Carbon;
use App\SmNotification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\BBB\Entities\BbbSetting;
use Brian2694\Toastr\Facades\Toastr;
use Modules\BBB\Entities\BbbMeeting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Modules\BBB\Entities\BbbMeetingUser;
use Illuminate\Contracts\Support\Renderable;
use Modules\BBB\Entities\BbbVirtualClass;
use Modules\RolePermission\Entities\InfixRole;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;



class BbbMeetingController extends Controller
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
        $data['setting'] = BbbSetting::first();
        $data['user'] = Auth::user();
         $data['roles'] = InfixRole::where(function ($q) {
            $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
             })->whereNotIn('id', [1, 2])->get();
        $data['instructors'] = User::select('id', 'full_name')->where('role_id', 4)->get();
        // $data['classes'] = VirtualClass::select('id', 'title')->where('host','BBB')->where('type',1)->latest()->get();
         $data['classes'] =SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();


        if (Auth::user()->role_id == 4) {
            $data['default_settings'] = BbbSetting::first();
            $data['meetings'] = BbbMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })
                ->orWhere('created_by', Auth::user()->id)
               
                ->get();
        } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
            $data['meetings'] = BbbMeeting::orderBy('id', 'DESC')->get();
        } else {
            $data['meetings'] = BbbMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return  $query->where('user_id', Auth::user()->id);
            })
               
                ->get();
        }
        $data['env']['security_salt'] = config('bigbluebutton.BBB_SECURITY_SALT');
        $data['env']['server_base_url'] = config('bigbluebutton.BBB_SERVER_BASE_URL');
        return view('bbb::meeting.meeting', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('bbb::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $topic = $request->get('topic');
        $instructor_id = $request->get('instructor_id');
        $class_id = $request->get('class_id');
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
        $duration = "240";
        $time_start_before = $request->get('time_start_before');
        $description=$request->get('description');
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
        }else{
             $fileName="";
        }

        $request->validate([
            'member_type' => 'required',
            'participate_ids' => 'required|array',
            'topic' => 'required',
            'attendee_password' => 'required',
            'moderator_password' => 'required',
            'date' => 'required',
            'time' => 'required',

        ]);

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
                'record' => (bool) $record,
                'duration' => $duration,
                'isBreakout' => (bool) $is_breakout,
                'moderatorOnlyMessage' => $moderator_only_message,
                'autoStartRecording' => (bool) $auto_start_recording,
                'allowStartStopRecording' => (bool) $allow_start_stop_recording,
                'webcamsOnlyForModerator' => (bool) $webcams_only_for_moderator,
                'copyright' => $copyright,
                'muteOnStart' => (bool) $mute_on_start,
                'lockSettingsDisableMic' => (bool) $lock_settings_disable_mic,
                'lockSettingsDisablePrivateChat' =>(bool) $lock_settings_disable_private_chat,
                'lockSettingsDisablePublicChat' => (bool)$lock_settings_disable_public_chat,
                'lockSettingsDisableNote' =>(bool) $lock_settings_disable_note,
                'lockSettingsLockedLayout' => (bool) $lock_settings_locked_layout,
                'lockSettingsLockOnJoin' => (bool) $lock_settings_lock_on_join,
                'lockSettingsLockOnJoinConfigurable' => (bool) $lock_settings_lock_on_join_configurable,
                'guestPolicy' => $guest_policy,
                'redirect' =>(bool)  $redirect,
                'joinViaHtml5' => (bool) $join_via_html5,
                'state' => $state,
            ]);

            if ($createMeeting) {
                $local_meeting = BbbMeeting::create([
                    'meeting_id' => $createMeeting['meetingID'],
                    'instructor_id' => $instructor_id,
                    'class_id' => $class_id,
                    'topic' => $topic,
                    'attendee_password' => $attendee_password,
                    'moderator_password' => $moderator_password,
                    'date' => $date,
                    'time' => $time,
                    'datetime' => $datetime,
                    'logo' =>$fileName,
                    'description'=>$description,
                    'welcome_message' => $welcome_message,
                    'dial_number' => $dial_number,
                    'max_participants' => $max_participants,
                    'logout_url' => $logout_url,
                    'record' => $record,
                    'duration' => $duration,
                    'time_start_before' =>$time_start_before,
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
                    'start_time' =>  Carbon::parse($start_date)->toDateTimeString(),
                    'end_time' =>  Carbon::parse($start_date)->addMinute($request['duration'])->toDateTimeString(),
                    'created_by' => Auth::user()->id,

                ]);
            }


            //$user = new BbbMeetingUser();
            // $user->meeting_id = $local_meeting->id;
            // $user->user_id = $instructor_id;
            // $user->moderator = 1;
            // $user->save();


            $local_meeting->participates()->attach($request['participate_ids']);
            $this->setNotificaiton($request['participate_ids'], $request['member_type'], $updateStatus = 0);
            DB::commit();

            if ( $local_meeting) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }

            Toastr::success('Class updated successful', 'Success');
            return redirect()->route('bbb.meetings');
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
//        $user = Auth::user();
        $localMeetingData = BbbMeeting::findOrFail($id);
//        $isRunning = Bigbluebutton::isMeetingRunning($localMeetingData->meeting_id);


//        if (!$isRunning) {
//            $joinUrl = Bigbluebutton::start([
//                'meetingID' => $localMeetingData->meeting_id,
//                'moderatorPW' => $localMeetingData->moderator_password,
//                'userName' => $user->name,
//            ]);
//        } else {
//            $joinUrl = Bigbluebutton::start([
//                'meetingID' => $localMeetingData->meeting_id,
//                'attendeePW' => $localMeetingData->attendee_password,
//                'userName' => $user->name,
//            ]);
//        }
//        $joinUrl2 = Bigbluebutton::start([
//            'meetingID' => $localMeetingData->meeting_id,
//            'attendeePW' => $localMeetingData->attendee_password,
//            'userName' => 'USer',
//        ]);

//        $localMeetingData->joinUrl = $joinUrl;
//        $localMeetingData->joinUrl2 = $joinUrl2;
        return view('bbb::meeting.meetingDetails', compact('localMeetingData'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['setting'] = BbbSetting::first();
        $data['user'] = Auth::user();
        $data['editdata'] = BbbMeeting::findOrFail($id);
        $data['meetings'] = BbbMeeting::orderBy('id', 'DESC')->get();
        $data['instructors'] = User::select('id', 'full_name')->where('role_id', 4)->get();

        $data['participate_ids'] = DB::table('bbb_meeting_users')->where('meeting_id',$id)->select('user_id')->pluck('user_id');

        $data['roles'] = InfixRole::where(function ($q) {
            $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
             })->whereNotIn('id', [1, 2])->get();
        $data['user_type'] = $data['editdata']->participates[0]['role_id'];     
        // $data['classes'] = Class::select('id', 'title')->where('host','BBB')->where('type',1)->latest()->get();
        $data['classes'] =SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        $data['env']['security_salt'] = config('bigbluebutton.BBB_SECURITY_SALT');
        $data['env']['server_base_url'] = config('bigbluebutton.BBB_SERVER_BASE_URL');


        $data['userList'] = User::where('role_id', $data['user_type'])
                ->where('school_id', Auth::user()->school_id)
                ->whereIn('id',$data['participate_ids'])
                ->select('id', 'full_name', 'role_id', 'school_id')->get();
            if (Auth::user()->role_id != 1) {
                if (Auth::user()->id != $data['editdata']->created_by) {
                    Toastr::error('Meeting is created by other, you could not modify !', 'Failed');
                    return redirect()->back();
                }
            }

        return view('bbb::meeting.meeting', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
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

        $request->validate([
            'topic' => 'required',
            'attendee_password' => 'required',
            'date' => 'required',
            'time' => 'required',

        ]);


       $system_meeting= BbbMeeting::updateOrCreate([
            'id' => $id
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
            'start_time' =>  Carbon::parse($start_date)->toDateTimeString(),
            'end_time' =>  Carbon::parse($start_date)->addMinute($request['duration'])->toDateTimeString(),
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
                'logo' =>  $fileName
            ]);
        }

       
            $system_meeting->participates()->detach();
            $system_meeting->participates()->attach($request['participate_ids']);
       

        Toastr::success('Meeting updated successful', 'Success');
        return redirect()->route('bbb.meetings');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $meeting = BbbMeeting::findOrFail($id);
       
        BbbMeetingUser::where('meeting_id', $meeting->id)->delete();

        $meeting->delete();
        Toastr::success('Class Delete successful', 'Success');
        return redirect()->route('bbb.meetings');
    }

    public function meetingStart($id)
    {
        if(!Auth::user()){
            Toastr::error('Access Failed!', 'Failed');
            return redirect()->back();
        }
        $type = "Moderator";
        $meetingID = $id;

        $meeting = Bigbluebutton::getMeetingInfo([
            'meetingID' => $meetingID,
        ]);
        $localBbbMeeting = BbbMeeting::where('meeting_id', $id)->first();

        if(!$localBbbMeeting){
            $localBbbMeeting = BbbVirtualClass::where('meeting_id', $id)->first();
        }

        abort_if(!$localBbbMeeting, 404);

        if (count($meeting) == 0) {
            $createMeeting = Bigbluebutton::create([
                'meetingID' => $localBbbMeeting->meeting_id,
                'meetingName' => $localBbbMeeting->topic,
                'attendeePW' => $localBbbMeeting->attendee_password,
                'moderatorPW' => $localBbbMeeting->moderator_password,
                'welcomeMessage' => $localBbbMeeting->welcome_message,
                'dialNumber' => $localBbbMeeting->dial_number,
                'maxParticipants' => $localBbbMeeting->max_participants,
                'logoutUrl' => $localBbbMeeting->logout_url,
                'record' => $localBbbMeeting->record,
                'duration' => $localBbbMeeting->duration,
                'isBreakout' => $localBbbMeeting->is_breakout,
                'moderatorOnlyMessage' => $localBbbMeeting->moderator_only_message,
                'autoStartRecording' => $localBbbMeeting->auto_start_recording,
                'allowStartStopRecording' => $localBbbMeeting->allow_start_stop_recording,
                'webcamsOnlyForModerator' => $localBbbMeeting->webcams_only_for_moderator,
                'copyright' => $localBbbMeeting->copyright,
                'muteOnStart' => $localBbbMeeting->mute_on_start,
                'lockSettingsDisableMic' => $localBbbMeeting->lock_settings_disable_mic,
                'lockSettingsDisablePrivateChat' => $localBbbMeeting->lock_settings_disable_private_chat,
                'lockSettingsDisablePublicChat' => $localBbbMeeting->lock_settings_disable_public_chat,
                'lockSettingsDisableNote' => $localBbbMeeting->lock_settings_disable_note,
                'lockSettingsLockedLayout' => $localBbbMeeting->lock_settings_locked_layout,
                'lockSettingsLockOnJoin' => $localBbbMeeting->lock_settings_lock_on_join,
                'lockSettingsLockOnJoinConfigurable' => $localBbbMeeting->lock_settings_lock_on_join_configurable,
                'guestPolicy' => $localBbbMeeting->guest_policy,
                'redirect' => $localBbbMeeting->redirect,
                'joinViaHtml5' => $localBbbMeeting->join_via_html5,
                'state' => $localBbbMeeting->state,
            ]);

            $meeting = Bigbluebutton::getMeetingInfo([
                'meetingID' => $meetingID,
            ]);
        }
        if ($type == "Moderator") {
            $url = Bigbluebutton::start([
                'meetingID' => $meetingID,
                'password' => $meeting['moderatorPW'],
                'userName' => Auth::user()->full_name,
            ]);
        } else {
            $url = Bigbluebutton::start([
                'meetingID' => $meetingID,
                'password' => $meeting['attendeePW'],
                'userName' => Auth::user()->full_name,
            ]);
        }
        return redirect()->to($url);
    }
    public function meetingJoin($id){
        
        if(!Auth::user()){
            Toastr::error('Access Failed!', 'Failed');
            return redirect()->back();
        }
        $type = "Attendee";
        $meetingID = $id;

        $meeting = Bigbluebutton::getMeetingInfo([
            'meetingID' => $meetingID,
        ]);
        $localBbbMeeting = BbbMeeting::where('meeting_id', $id)->first();

        if(!$localBbbMeeting){
            $localBbbMeeting = BbbVirtualClass::where('meeting_id', $id)->first();
        }

        abort_if(!$localBbbMeeting, 404);

        if (count($meeting) == 0) {
            $createMeeting = Bigbluebutton::create([
                'meetingID' => $localBbbMeeting->meeting_id,
                'meetingName' => $localBbbMeeting->topic,
                'attendeePW' => $localBbbMeeting->attendee_password,
                'moderatorPW' => $localBbbMeeting->moderator_password,
                'welcomeMessage' => $localBbbMeeting->welcome_message,
                'dialNumber' => $localBbbMeeting->dial_number,
                'maxParticipants' => $localBbbMeeting->max_participants,
                'logoutUrl' => $localBbbMeeting->logout_url,
                'record' => $localBbbMeeting->record,
                'duration' => $localBbbMeeting->duration,
                'isBreakout' => $localBbbMeeting->is_breakout,
                'moderatorOnlyMessage' => $localBbbMeeting->moderator_only_message,
                'autoStartRecording' => $localBbbMeeting->auto_start_recording,
                'allowStartStopRecording' => $localBbbMeeting->allow_start_stop_recording,
                'webcamsOnlyForModerator' => $localBbbMeeting->webcams_only_for_moderator,
                'copyright' => $localBbbMeeting->copyright,
                'muteOnStart' => $localBbbMeeting->mute_on_start,
                'lockSettingsDisableMic' => $localBbbMeeting->lock_settings_disable_mic,
                'lockSettingsDisablePrivateChat' => $localBbbMeeting->lock_settings_disable_private_chat,
                'lockSettingsDisablePublicChat' => $localBbbMeeting->lock_settings_disable_public_chat,
                'lockSettingsDisableNote' => $localBbbMeeting->lock_settings_disable_note,
                'lockSettingsLockedLayout' => $localBbbMeeting->lock_settings_locked_layout,
                'lockSettingsLockOnJoin' => $localBbbMeeting->lock_settings_lock_on_join,
                'lockSettingsLockOnJoinConfigurable' => $localBbbMeeting->lock_settings_lock_on_join_configurable,
                'guestPolicy' => $localBbbMeeting->guest_policy,
                'redirect' => $localBbbMeeting->redirect,
                'joinViaHtml5' => $localBbbMeeting->join_via_html5,
                'state' => $localBbbMeeting->state,
            ]);

            $meeting = Bigbluebutton::getMeetingInfo([
                'meetingID' => $meetingID,
            ]);
        }
        if ($type == "Moderator") {
            $url = Bigbluebutton::start([
                'meetingID' => $meetingID,
                'password' => $meeting['moderatorPW'],
                'userName' => Auth::user()->full_name,
            ]);
        } else {
            $url = Bigbluebutton::start([
                'meetingID' => $meetingID,
                'password' => $meeting['attendeePW'],
                'userName' => Auth::user()->full_name,
            ]);
        }
        return redirect()->to($url);
    }
    public function meetingStartAsAttendee($course_id,$meeting_id)
    {
        if (Auth::check() && isEnrolled($course_id, Auth::user()->id)) {
            $meeting = Bigbluebutton::getMeetingInfo([
                'meetingID' => $meeting_id,
            ]);
            $localBbbMeeting = BbbMeeting::where('meeting_id',$meeting_id)->first();

            if (!$localBbbMeeting->isRunning()){
                Toastr::error('Class Not Running', 'Failed');
                return redirect()->back();
            }
            $url = Bigbluebutton::start([
                'meetingID' => $meeting_id,
                'password' => $localBbbMeeting->attendee_password,
                'userName' => Auth::user()->name,
            ]);
            return redirect()->to($url);
        }else{
            Toastr::error('Access Failed!', 'Failed');
            return redirect()->back();
        }


    }

    public function userWiseUserList(Request $request)
    {
        if ($request->has('user_type')) {
            $userList =  User::where('role_id', $request['user_type'])
                ->where('school_id', Auth::user()->school_id)
                ->select('id', 'full_name', 'school_id')->get();
            return response()->json([
                'users' => $userList
            ]);
        }
    }


    private function setNotificaiton($users, $role_id, $updateStatus)
    {
        $now = Carbon::now('utc')->toDateTimeString();
        $school_id = Auth::user()->school_id;
        $notification_datas = [];

        if ($updateStatus == 1) {
            foreach ($users as $key => $user) {
                array_push(
                    $notification_datas,
                    [
                        'user_id'       => $user,
                        'role_id'       => $role_id,
                        'school_id'     => $school_id,
                        'date'          => date('Y-m-d'),
                        'academic_id'     => getAcademicId(),
                        'message'       => 'bbb meeting is updated by ' . Auth::user()->full_name . '',
                        'url'           => route('bbb.meetings'),
                        'created_at'    => $now,
                        'updated_at'    => $now
                    ]
                );
            };
        } else {
            foreach ($users as $key => $user) {
                array_push(
                    $notification_datas,
                    [
                        'user_id'       => $user,
                        'role_id'       => $role_id,
                        'school_id'     => $school_id,
                        'date'          => date('Y-m-d'),
                        'academic_id'     => getAcademicId(),
                        'message'       => 'bbb meeting is created by ' . Auth::user()->full_name . ' with you',
                        'url'           => route('bbb.meetings'),
                        'created_at'    => $now,
                        'updated_at'    => $now
                    ]
                );
            };
        }
        SmNotification::insert($notification_datas);
    }

}
