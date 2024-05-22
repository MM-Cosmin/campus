<?php

namespace Modules\Zoom\Repositories\Eloquents;

use App\User;
use App\SmWeekend;
use Carbon\Carbon;
use App\SmNotification;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Zoom\Entities\ZoomMeeting;
use Modules\Zoom\Entities\ZoomSetting;
use App\Repositories\Eloquents\BaseRepository;
use Modules\RolePermission\Entities\InfixRole;
use Modules\Zoom\Repositories\Interfaces\ZoomRepositoryInterface;
use Modules\Zoom\Repositories\Interfaces\VirtualMeetingRepositoryInterface;

class VirtualMeetingRepository extends BaseRepository implements VirtualMeetingRepositoryInterface
{
    protected $zoomRepository;
    public function __construct(
        ZoomMeeting $model,
        ZoomRepositoryInterface $zoomRepository
    ) {
       parent::__construct($model);
       $this->zoomRepository = $zoomRepository;
    }
    public function index()
    {
        $data = [];
        $data += $this->defaultPageData();
        return $data;
    }
    public function show($id)
    {
        $model = $this->model->where('meeting_id', $id)->first();
        $data['localMeetingData'] = $model;
        $data['results'] = $model;
        $data += $this->assign_days($model);
        return $data;
    }
    public function edit(int $id)
    {        
        $data['editData'] = $this->findById($id);   
        $data['participate_ids'] = DB::table('zoom_meeting_users')->where('meeting_id', $id)
        ->select('user_id')->pluck('user_id');
        $data['user_type'] = $data['editData']->participates[0]['role_id'];
        $data['userList'] = User::where('role_id', $data['user_type'])
            ->where('school_id', Auth::user()->school_id)
            ->whereIn('id', $data['participate_ids'])
            ->select('id', 'full_name', 'role_id', 'school_id')->get();
        $data += $this->defaultPageData();
        $data += $this->assign_ids($data['editData']);
        return $data;
    }
    private function defaultPageData()
    {
        date_default_timezone_set(generalSetting()->timeZone->time_zone);
        $data['default_settings'] = ZoomSetting::where('school_id', auth()->user()->school_id)->first();
        if(auth()->user()->role_id == 1){
            if(!$data['default_settings'] || !$data['default_settings']->api_key || !$data['default_settings']->secret_key ){
                Toastr::error(__('zoom.please add api key and secrect key first'), 'Failed');
                return redirect()->route('zoom.settings')->send();
            } 
        }else{
            Toastr::error(__('zoom.please add api key and secrect key first'), 'Failed');
            return redirect()->back()->send();
        }
        $data['default_settings']->makeHidden(['api_key', 'secret_key', 'created_at', 'updated_at']);
        $data['roles'] = InfixRole::where(function ($q) {
            $q->where('school_id', auth()->user()->school_id)->orWhere('type', 'System');
        })->whereNotIn('id', [1, 2])->get();
          
        $data['meetings'] = ZoomMeeting::orderBy('id', 'DESC')
        ->when(auth()->user()->role_id == 4, function($q) {
            $q->whereHas('participates', function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })->orWhere('created_by', auth()->user()->id);
        })->when(!in_array(auth()->user()->role_id, [1,4]), function($q){
            $q->whereHas('participates', function ($query) {
                return  $query->where('user_id', auth()->user()->id);
            });
        })->where('status', 1)->get();      

        $data['days'] = SmWeekend::orderby('order')->get(['id','name','order','zoom_order']);

        return $data;
    }
    public function meetingStore($request)
    {
        $zoomData = $this->zoomRepository->zoomData($request);
        $token = $this->zoomRepository->createZoomToken();
        $meeting_details = (object) Http::withToken($token)->post('https://api.zoom.us/v2/users/me/meetings', $zoomData)->json();
        $system_meeting =  ZoomMeeting::create($this->formattedParams($request, $meeting_details->id));
        $system_meeting->participates()->attach($request['participate_ids']);
        $this->setNotification($request['participate_ids'], $request['member_type'], $updateStatus = 0);
    }
    public function meetingUpdate($request, $id)
    {
        $model = $this->findById($id);
        $zoomData = $this->zoomRepository->zoomData($request);
        $token = $this->zoomRepository->createZoomToken();
        $meeting_details = (object) Http::withToken($token)->patch('https://api.zoom.us/v2/meetings/'.$model->meeting_id, $zoomData)->json();
        $system_meeting =  $model->update($this->formattedParams($request, null, $model));
        if (auth()->user()->role_id == 1) {
            $model->participates()->detach();
            $model->participates()->attach($request['participate_ids']);
        }
        $this->setNotification($request['participate_ids'], $request['member_type'], $updateStatus = 1);
    }
    private function formattedParams($request, $meeting_id = null, $model = null)
    {
        $fileName = "";
        if ($request->file('attached_file') != "") {
            $file = $request->file('attached_file');
            $fileName = $request['topic'] . time() . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/zoom-meeting/', $fileName);
            $fileName = 'public/uploads/zoom-meeting/' . $fileName;
        }
        if ($request->file('attached_file') != "" && $model) {
            if (file_exists($model->attached_file)) {
                unlink($model->attached_file);
            }
        }
        $days= $request->days;
        if(!empty($days)){
          $str_days_id=implode(',',$days);
        }
        $start_date = Carbon::parse($request['date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($request['time']));
        $params = [
                'topic' =>  $request['topic'],
                'description' =>  $request['description'],
                'date_of_meeting' =>  $request['date'],
                'time_of_meeting' =>  $request['time'],
                'meeting_duration' =>  $request['duration'],
                'time_before_start' =>$request['time_start_before'],
                'host_video' => $request['host_video'],
                'participant_video' => $request['participant_video'],
                'join_before_host' => $request['join_before_host'],
                'mute_upon_entry' => $request['mute_upon_entry'],
                'waiting_room' => $request['waiting_room'],
                'audio' => $request['audio'],
                'auto_recording' => $request->has('auto_recording') ? $request['auto_recording'] : 'none',
                'approval_type' => $request['approval_type'],
                'is_recurring' =>  $request['is_recurring'],
                'recurring_type' =>   $request['is_recurring'] == 1 ? $request['recurring_type'] : null,
                'recurring_repect_day' =>   $request['is_recurring'] == 1 ? $request['recurring_repect_day'] : null,
                'weekly_days' => $request['recurring_type'] == 2 ? $str_days_id : null,
                'recurring_end_date' =>  $request['is_recurring'] == 1 ?  $request['recurring_end_date'] : null,              
                'password' =>  $request['password'],
                'start_time' =>  Carbon::parse($start_date)->toDateTimeString(),
                'end_time' =>  Carbon::parse($start_date)->addMinute($request['duration'])->toDateTimeString(),
                'attached_file' =>  $fileName,
                
            ];
            if(!$model) {
                $params['created_by'] = Auth::user()->id;
                $params['school_id'] = Auth::user()->school_id;
                $params['meeting_id'] = (string)$meeting_id;
            }
            return $params;        
    }
    private function setNotification($users, $role_id, $updateStatus)
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
                        'academic_id'     => getAcademicId(),
                        'date'          => date('Y-m-d'),
                        'message'       => 'Zoom meeting is updated by ' . Auth::user()->full_name . '',
                        'url'           => route('zoom.meetings'),
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
                        'academic_id'     => getAcademicId(),
                        'date'          => date('Y-m-d'),
                        'message'       => 'Zoom meeting is created by ' . Auth::user()->full_name . ' with you',
                        'url'           => route('zoom.meetings'),
                        'created_at'    => $now,
                        'updated_at'    => $now
                    ]
                );
            };
        }
        SmNotification::insert($notification_datas);
    }
    private function assign_ids($model)
    {
        $day_ids = $model->weekly_days;
        $days = explode(',', $day_ids);
        $assign_day = [];
        foreach ($days as $dayId) {
            $assign_day[] = $dayId;
        }
        $data['assign_day'] = $assign_day;
        return $data;
    }
    private function assign_days($model)
    {
        $day_ids = $model->weekly_days;
        $data['assign_day'] = [];
        if ($day_ids != null) {
            $days = explode(',', $day_ids);
            $assign_day = [];
            foreach ($days as $dayId) {
                $assign_day[] = SmWeekend::where('zoom_order', $dayId)->first();
            }
            $data['assign_day'] = $assign_day;
        }
        return $data;
    }
    public function deleteById(int $modelId): bool
    {
        $model = $this->findById($modelId);
        if (Auth::user()->role_id != 1) {
            if (Auth::user()->id != $model->created_by) {
                Toastr::error('Meeting is created by other, you could not DELETE !', 'Failed');
                return redirect()->back();
            }
        }
        $token = $this->zoomRepository->createZoomToken();
        $meeting_details = Http::withToken($token)->delete('https://api.zoom.us/v2/meetings/'.$model->meeting_id)->json();
        if (file_exists($model->attached_file)) {
            unlink($model->attached_file);
        }
        $model->delete();
        return true;
    }
}