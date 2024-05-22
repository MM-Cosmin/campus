<?php

namespace Modules\Zoom\Repositories\Eloquents;

use Carbon\Carbon;
use App\SmGeneralSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Zoom\Entities\ZoomSetting;
use Modules\Zoom\Entities\VirtualClass;
use Modules\Zoom\Repositories\Interfaces\ZoomRepositoryInterface;

class ZoomRepository implements ZoomRepositoryInterface
{
    protected $account_id, $client_id, $password;
    public function __construct()
    {
        $setting = ZoomSetting::first();
        $this->account_id = $setting->account_id;
        $this->client_id = $setting->api_key;
        $this->password = $setting->secret_key;
    }
    public function index()
    {
        
    }
    public function createZoom()
    {

    }
    public function createZoomToken()
    {
        $response = Http::withBasicAuth($this->client_id, $this->password)->post('https://zoom.us/oauth/token?grant_type=account_credentials&account_id=' . $this->account_id)->json();
        return $response['access_token'] ?? '';
    }
    public function isTimeAvailableForMeeting($request, $id)
    {
        if (isset($request['teacher_ids'])) {
            $teacherList = [$request['teacher_ids']];
        } else {
            $teacherList = [auth()->user()->id];
        }

        if ($id != 0) {
            $meetings = VirtualClass::where('date_of_meeting', Carbon::parse($request['date'])->format("m/d/Y"))
                ->where('class_id', $request['class'])
                ->where('id', '!=', $id)
                ->where('section_id', $request['section'])
                ->where('school_id', auth()->user()->school_id)
                ->whereHas('teachers', function ($q) use ($teacherList) {
                    $q->whereIn('user_id', $teacherList);
                })
                ->get();
        } else {
            $meetings = VirtualClass::where('date_of_meeting', Carbon::parse($request['date'])->format("m/d/Y"))
                ->where('class_id', $request['class'])
                ->where('section_id', $request['section'])
                ->where('school_id', auth()->user()->school_id)
                ->whereHas('teachers', function ($q) use ($teacherList) {
                    $q->whereIn('user_id', $teacherList);
                })
                ->get();
        }
        if ($meetings->count() == 0) {
            return false;
        }
        $checkList = [];

        foreach ($meetings as $key => $meeting) {
            $new_time = Carbon::parse($request['date'] . ' ' . date("H:i:s", strtotime($request['time'])));
            $strat_time = Carbon::parse($meeting->date_of_meeting . ' ' . $meeting->time_of_meeting);
            $end_time = Carbon::parse($meeting->date_of_meeting . ' ' . $meeting->time_of_meeting)->addMinute($meeting->meeting_duration);

            if ($new_time->between(Carbon::parse($meeting->start_time), Carbon::parse($meeting->end_time))) {
                array_push($checkList, $meeting->time_of_meeting);
            }
        }
        if (count($checkList) > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function zoomData($request)
    {
        $days= $request->days;
        if(!empty($days)){
          $str_days_id=implode(',',$days);
        }
        $GSetting = SmGeneralSettings::where('school_id', auth()->user()->school_id)->first();
        $start_date = Carbon::parse($request['date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($request['time']));
        $start_time = Carbon::parse($start_date)->format("Y-m-d\TH:i:s");
        $zoomData = [
            "topic" => $request['topic'],
            "type" => $request['is_recurring'] == 1 ? 8 : 2,
            "duration" => $request['duration'],
            "timezone" => $GSetting->timeZone->time_zone,
            "password" => $request['password'],
            "start_time" => $start_time,
            "agenda" => 'InfixEdu Live Class',
            "settings" => [
                'join_before_host' => $this->setTrueFalseStatus($request['join_before_host']),
                'host_video' => $this->setTrueFalseStatus($request['host_video']),
                'participant_video' => $this->setTrueFalseStatus($request['participant_video']),
                'mute_upon_entry' => $this->setTrueFalseStatus($request['mute_upon_entry']),
                'waiting_room' => $this->setTrueFalseStatus($request['waiting_room']),
                'audio' => $request['audio'],
                'auto_recording' => $request['auto_recording'] ? $request['auto_recording'] : 'none',
                'approval_type' => $request['approval_type'],
            ]
        ];
        if ($request['is_recurring'] == 1) {
            $end_date = Carbon::parse($request['recurring_end_date'])->endOfDay();
            $zoomData['recurrence'] = [
                'type' => $request['recurring_type'],
                'repeat_interval' => $request['recurring_repect_day'],
                'end_date_time' => $end_date
            ];
            if($request->recurring_type==2) {
                $zoomData['recurrence'] += [
                    'weekly_days'=>$str_days_id
                ];
            }
        }
        return $zoomData;
    }
    private function setTrueFalseStatus($value)
    {
        if ($value == 1) {
            return true;
        }
        return false;
    }
}