<?php

namespace Modules\BBB\Http\Controllers\Api;

use App\User;
use App\SmClass;
use App\ApiBaseMethod;
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
use Modules\BBB\Entities\BbbMeeting;

class BbbApiController extends Controller
{
    public function __construct()
    {
        Artisan::call('config:clear');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id = null)
    {
        try {

            if (Auth::user()->role_id == 4) {
                $data['default_settings'] = BbbSetting::first();
                $st_id = SmStaff::where('user_id', Auth::user()->id)->first();
                $meetings = BbbVirtualClass::orderBy('id', 'DESC')->whereHas('teachers', function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })->get();

                foreach ($meetings as $meeting) {

                    if ($meeting->getCurrentStatusAttribute() == 'started') {

                        if (Auth::user()->role_id == 1) {
                            $teahcer_id = DB::table('bbb_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                            $teahcer_id = $teahcer_id->user_id;
                        } else {
                            $teahcer_id = 0;
                        }

                        if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                            $meeting->status = 'started';
                        } else {
                            $meeting->status = 'join';
                        }
                    } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {
                        $meeting->status = 'waiting';
                    } else {
                        $meeting->status = 'closed';
                    }
                }
                $data['meetings'] = $meetings;
            } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
                $meetings = BbbVirtualClass::orderBy('id', 'DESC')->get();

                foreach ($meetings as $meeting) {
                    if (Auth::user()->role_id == 1) {
                        $teahcer_id = DB::table('bbb_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                        $teahcer_id = $teahcer_id->user_id;
                    } else {
                        $teahcer_id = 0;
                    }
                    if ($meeting->getCurrentStatusAttribute() == 'started') {

                        if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                            $meeting->status = 'started';
                        } else {
                            $meeting->status = 'join';
                        }
                    } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                        $meeting->status = 'waiting';
                    } else {
                        $meeting->status = 'closed';
                    }
                }
                $data['meetings'] = $meetings;
            } elseif (Auth::user()->role_id == 2 || Auth::user()->role_id == 3) {
                $studentRecord = StudentRecord::where('id', $id)->first();
                $class_id = $studentRecord->class_id;
                $section_id = $studentRecord->section_id;
                $meetings = BbbVirtualClass::orderBy('id', 'DESC')->where('class_id', $class_id)->where('section_id', $section_id)->orwhere('section_id', null)->get();

                foreach ($meetings as $meeting) {
                    if (Auth::user()->role_id == 1) {
                        $teahcer_id = DB::table('bbb_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                        $teahcer_id = $teahcer_id->user_id;
                    } else {
                        $teahcer_id = 0;
                    }
                    if ($meeting->getCurrentStatusAttribute() == 'started') {

                        if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                            $meeting->status = 'started';
                        } else {
                            $meeting->status = 'join';
                        }
                    } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                        $meeting->status = 'waiting';
                    } else {
                        $meeting->status = 'closed';
                    }
                }
                $data['meetings'] = $meetings;
            }else{
                
            }
            return ApiBaseMethod::sendResponse($data, null);
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }

    public function meetings()
    {
        try {
            if (Auth::user()->role_id == 4) {
                $meetings =   BbbMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
                    ->orWhere('created_by', Auth::user()->id)

                    ->get();

                foreach ($meetings as $meeting) {
                    if (Auth::user()->role_id == 1) {
                        $teahcer_id = DB::table('bbb_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                        $teahcer_id = $teahcer_id->user_id;
                    } else {
                        $teahcer_id = 0;
                    }
                    if ($meeting->getCurrentStatusAttribute() == 'started') {

                        if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                            $meeting->status = 'started';
                        } else {
                            $meeting->status = 'join';
                        }
                    } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                        $meeting->status = 'waiting';
                    } else {
                        $meeting->status = 'closed';
                    }
                }
                $data['meetings'] = $meetings;
            } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
                $meetings =  BbbMeeting::orderBy('id', 'DESC')->get();

                foreach ($meetings as $meeting) {
                    if (Auth::user()->role_id == 1) {
                        $teahcer_id = DB::table('bbb_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                        $teahcer_id = $teahcer_id->user_id;
                    } else {
                        $teahcer_id = 0;
                    }
                    if ($meeting->getCurrentStatusAttribute() == 'started') {

                        if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                            $meeting->status = 'started';
                        } else {
                            $meeting->status = 'join';
                        }
                    } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                        $meeting->status = 'waiting';
                    } else {
                        $meeting->status = 'closed';
                    }
                }
                $data['meetings'] = $meetings;
            } else {
                $meetings = BbbMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                    return  $query->where('user_id', Auth::user()->id);
                })

                    ->get();
                foreach ($meetings as $meeting) {
                    if (Auth::user()->role_id == 1) {
                        $teahcer_id = DB::table('bbb_virtual_class_teachers')->where('meeting_id', $meeting->id)->first(['id', 'user_id']);
                        $teahcer_id = $teahcer_id->user_id;
                    } else {
                        $teahcer_id = 0;
                    }
                    if ($meeting->getCurrentStatusAttribute() == 'started') {

                        if (Auth::user()->role_id == 1 || Auth::user()->id == $meeting->created_by || $teahcer_id == Auth::user()->id) {

                            $meeting->status = 'started';
                        } else {
                            $meeting->status = 'join';
                        }
                    } else if ($meeting->getCurrentStatusAttribute() == 'waiting') {

                        $meeting->status = 'waiting';
                    } else {
                        $meeting->status = 'closed';
                    }
                }
                $data['meetings'] = $meetings;
            }
            return ApiBaseMethod::sendResponse($data, null);
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Error.', $e->getMessage());
        }
    }

    public function meetingJoin($id)
    {

        if (!Auth::user()) {
            return ApiBaseMethod::sendError('Error.', "Failed");
        }
        $type = "Attendee";
        $meetingID = $id;

        $meeting = Bigbluebutton::getMeetingInfo([
            'meetingID' => $meetingID,
        ]);
        $localBbbMeeting = BbbMeeting::where('meeting_id', $id)->first();

        if (!$localBbbMeeting) {
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
        return $url;
    }
}
