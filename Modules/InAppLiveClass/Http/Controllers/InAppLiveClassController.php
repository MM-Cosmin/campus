<?php

namespace Modules\InAppLiveClass\Http\Controllers;


use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\InAppLiveClass\Entities\InAppLiveClassMeeting;
use Modules\InAppLiveClass\Entities\InAppLiveClassMeetingUser;
use Modules\InAppLiveClass\Services\RtcTokenBuilder;
use Modules\InAppLiveClass\Services\RtmTokenBuilder;
use Modules\VirtualClass\Entities\ClassComplete;

class InAppLiveClassController extends Controller
{

    public $appId;
    private $appCertificate;

    public function __construct()
    {
        $this->appId = Settings('agora_app_id');
        $this->appCertificate = Settings('agora_app_certificate');
    }

    public function index()
    {
        return view('inappliveclass::index');
    }

    public function store(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        UpdateGeneralSetting('agora_app_id', $request->agora_app_id);
        UpdateGeneralSetting('agora_app_certificate', $request->agora_app_certificate);

        GenerateGeneralSetting(SaasDomain());

        Toastr::success(trans('common.Operation successful'));
        return back();
    }


    public function classStore($data)
    {
        $topic = $data['topic'];
        $description = $data['description'];
        $instructor_id = $data['instructor_id'];
        $duration = $data['duration'];
        $class_id = $data['class_id'];
        $date = $data['date'];
        $time = $data['time'];
        $datetime = $date . " " . $time;
        $datetime = strtotime($datetime);

        $meeting_id = date('ymdhmi');

        try {
            $local_meeting = InAppLiveClassMeeting::create([
                'meeting_id' => $meeting_id,
                'instructor_id' => $instructor_id,
                'class_id' => $class_id,
                'duration' => $duration,
                'topic' => $topic,
                'date' => $date,
                'time' => $time,
                'datetime' => $datetime,
                'description' => $description,
                'created_by' => Auth::user()->id,
            ]);


            $user = new InAppLiveClassMeetingUser();
            $user->meeting_id = $local_meeting->id;
            $user->user_id = $instructor_id;
            $user->save();


            if ($local_meeting) {
                $result['message'] = '';
                $result['type'] = true;
                return $result;
            } else {
                $result['message'] = '';
                $result['type'] = false;
            }
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
            $result['type'] = false;
            return $result;
        }
    }

    public function show(int $id)
    {

        $meeting = InAppLiveClassMeeting::findOrFail($id);
        return view('inappliveclass::meeting.index', compact('meeting'));
    }

    public function getRTCToken(string $channelName, bool $isHost): string
    {
        $role = $isHost ? RtcTokenBuilder::RolePublisher : RtcTokenBuilder::RoleAttendee;

        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        return RtcTokenBuilder::buildTokenWithUserAccount($this->appId, $this->appCertificate, $channelName, null, $role, $privilegeExpiredTs);
    }

    public function getRTMToken($channelName): string
    {
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        return RtmTokenBuilder::buildToken($this->appId, $this->appCertificate, $channelName, null, $privilegeExpiredTs);
    }

    public function destroy(int $id)
    {
        $meeting = InAppLiveClassMeeting::findOrFail($id);
        InAppLiveClassMeetingUser::where('meeting_id', $meeting->id)->delete();
        $meeting->delete();
        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }


    public function joinToAgora($id)
    {
        $user = auth()->user();
        $session = InAppLiveClassMeeting::with('class')->findOrFail($id);
        $isComplete = null;
        if ($user->role_id == 3) {
            $streamRole = 'audience';
            $isComplete = ClassComplete::where('user_id', $user->id)->where('meeting_id', $session->id)->where('status', 1)->first();
        } else {
            $streamRole = 'host';
        }
        $channelName = "session_$session->id";
        $accountName = $user->name;


        $isHost = ($streamRole === 'host');
        $appId = $this->appId;
        $rtcToken = $this->getRTCToken($channelName, $isHost);
        $rtmToken = $this->getRTMToken($accountName);

        $data = [
            'session' => $session,
            'isHost' => $isHost,
            'appId' => $appId,
            'accountName' => $accountName,
            'channelName' => $channelName,
            'rtcToken' => $rtcToken,
            'rtmToken' => $rtmToken,
            'streamRole' => $streamRole,
            'notStarted' => (!$isHost and empty($isComplete)),
            'streamStartAt' => time()
        ];

        return view('inappliveclass::meeting.index', $data);
    }

    public function endAgora($id)
    {
        $session = InAppLiveClassMeeting::findOrFail($id);;

        if (!empty($session)) {
            $session->update([
                'end_at' => time()
            ]);

            return response()->json([
                'code' => 200
            ]);

        }

        return response()->json([
            'code' => 422
        ]);
    }
}
