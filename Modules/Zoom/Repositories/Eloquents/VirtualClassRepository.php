<?php

namespace Modules\Zoom\Repositories\Eloquents;

use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmStudent;
use App\SmWeekend;
use Carbon\Carbon;
use App\SmNotification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Http;
use Modules\Zoom\Entities\ZoomSetting;
use Modules\Zoom\Entities\VirtualClass;
use App\Repositories\Eloquents\BaseRepository;
use Modules\Zoom\Repositories\Interfaces\ZoomRepositoryInterface;
use Modules\Zoom\Repositories\Interfaces\VirtualClassRepositoryInterface;

class VirtualClassRepository extends BaseRepository implements VirtualClassRepositoryInterface
{
    protected $zoomRepository;
    public function __construct(
        VirtualClass $model,
        ZoomRepositoryInterface $zoomRepository
    ) {
        parent::__construct($model);
        $this->zoomRepository = $zoomRepository;
    }
    public function index()
    {
        $data = [];
        $data += $this->defaultData();
        return $data;
    }
    public function show($meeting_id)   
    {
        $data = [];
        $model = $this->model->where('meeting_id', $meeting_id)->first();
        $data['localMeetingData'] = $model;
        $data['results'] = $model;
        $data += $this->assign_days($model);      
        return $data;
    }
    public function edit(int $id)
    {    
        $model = $this->findById($id);
        $token = $this->zoomRepository->createZoomToken();     
        $data['editData'] = VirtualClass::findOrFail($id);        
        $data['class_sections'] = SmSection::whereIn('id', $data['editData']->class->classSections->pluck('section_id'))->get();
        $data += $this->defaultData();
        $data += $this->assign_ids($data['editData']);
        return $data;
    }
    public function createClassWithZoom($request)
    {
       
    }
    public function classUpdate($request, $id)
    {
        $model = $this->findById($id);
        $token = $this->zoomRepository->createZoomToken();
        $zoomData = $this->zoomRepository->zoomData($request);     
        $meeting_details = Http::withToken($token)->patch('https://api.zoom.us/v2/meetings/'.$model->meeting_id, $zoomData)->json(); 
        $system_meeting = $model->update($this->formattedParams($request, null, $model));
        if (auth()->user()->role_id == 1) {
            $model->teachers()->detach();
            $model->teachers()->attach($request['teacher_ids']);
        }
        $student_ids = studentRecords($request, null, null)->pluck('student_id')->unique();
        $UserList = SmStudent::whereIn('id', $student_ids)
            ->select('user_id', 'role_id', 'parent_id')->get();
        $this->setNotification($UserList, $updateStatus = 1);
    }
    public function classStore($request)
    {
        $zoomData = $this->zoomRepository->zoomData($request);      
        $token = $this->zoomRepository->createZoomToken();
       
        $meeting_details = (object) Http::withToken($token)->post('https://api.zoom.us/v2/users/me/meetings', $zoomData)->json();
        if ($meeting_details) {        
            $system_meeting = VirtualClass::create($this->formattedParams($request, $meeting_details));
            if (auth()->user()->role_id == 1) {
                $system_meeting->teachers()->attach($request['teacher_ids']);
            } else {
                $system_meeting->teachers()->attach(auth()->user());
            }
            $student_ids = studentRecords($request, null, null)->pluck('student_id')->unique();
            $UserList = SmStudent::whereIn('id', $student_ids)->select('user_id', 'role_id', 'parent_id')->get();
            $this->setNotification($UserList, $updateStatus = 0);
        }

    }
    private function formattedParams($request, $meeting_details = null, $model = null): array
    {
        $start_date = Carbon::parse($request['date'])->format('Y-m-d') . ' ' . date("H:i:s", strtotime($request['time']));
        $days = $request->days;
        if (!empty($days)) {
            $str_days_id = implode(',', $days);
        }
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
        $params = [
            'class_id' => $request['class'] ?? null,
            'section_id' => $request['section'] ?? null,
            'topic' => $request['topic'],
            'description' => $request['description'],
            'date_of_meeting' => $request['date'],
            'time_of_meeting' => $request['time'],
            'meeting_duration' => $request['duration'],
            'time_before_start' => $request['time_before_start'],
            'host_video' => $request['host_video'],
            'participant_video' => $request['participant_video'],
            'join_before_host' => $request['join_before_host'],
            'mute_upon_entry' => $request['mute_upon_entry'],
            'waiting_room' => $request['waiting_room'],
            'audio' => $request['audio'],
            'auto_recording' => $request->has('auto_recording') ? $request['auto_recording'] : 'none',
            'approval_type' => $request['approval_type'],
            'is_recurring' => $request['is_recurring'],
            'recurring_type' => $request['is_recurring'] == 1 ? $request['recurring_type'] : null,
            'recurring_repect_day' => $request['is_recurring'] == 1 ? $request['recurring_repect_day'] : null,
            'weekly_days' => $request['recurring_type'] == 2 ? $str_days_id : null,
            'recurring_end_date' => $request['is_recurring'] == 1 ? $request['recurring_end_date'] : null,           
            'start_time' => Carbon::parse($start_date)->toDateTimeString(),
            'end_time' => Carbon::parse($start_date)->addMinute($request['duration'])->toDateTimeString(),
            'attached_file' => $fileName,
            'password' => $request['password']

        ];
        if(!$model && $meeting_details) {
            $params['meeting_id'] = (string) $meeting_details->id;            
            $params['created_by'] = auth()->user()->id;
            $params['school_id'] = auth()->user()->school_id;
        }
        return $params;
    }
    private function assign_days($model)
    {
        $day_ids = $model->weekly_days;
        $data = [];
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

    private function setNotification($users, $updateStatus)
    {
        $now = Carbon::now('utc')->toDateTimeString();
        $school_id = auth()->user()->school_id;
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
                        'message' => 'Zoom virtual class room details udpated',
                        'url' => route('zoom.virtual-class'),
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
                        'message' => 'Zoom virtual class room details udpated of your child',
                        'url' => route('zoom.virtual-class'),
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
                        'message' => 'Zoom virtual class room created for you',
                        'url' => route('zoom.virtual-class'),
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
                        'message' => 'Zoom virtual class room created for your child',
                        'url' => route('zoom.virtual-class'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            };
        }
        SmNotification::insert($notification_datas);
    }

    private function isTimeAvailableForMeeting($request, $id)
    {
        if (isset($request['teacher_ids'])) {
            $teacherList = [$request['teacher_ids']];
        } else {
            $teacherList = [auth()->user()->id];
        }

        if ($id != 0) {
            $meetings = $this->model->where('date_of_meeting', Carbon::parse($request['date'])->format("m/d/Y"))
                ->where('class_id', $request['class'])
                ->where('id', '!=', $id)
                ->where('section_id', $request['section'])
                ->where('school_id', auth()->user()->school_id)
                ->whereHas('teachers', function ($q) use ($teacherList) {
                    $q->whereIn('user_id', $teacherList);
                })
                ->get();
        } else {
            $meetings = $this->model->where('date_of_meeting', Carbon::parse($request['date'])->format("m/d/Y"))
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
    private function defaultData()
    {
        $user = auth()->user();
        if (teacherAccess()) {
            $teacher_info = SmStaff::where('user_id', $user->id)->first();
            $data['classes'] = $teacher_info->classes;
        } else {
            $data['classes'] = SmClass::get();
        }
        $data['teachers'] = SmStaff::where(function ($q) {
            $q->where('role_id', 4)->orWhere('previous_role_id', 4);
        })->get();
        if (!in_array($user->role_id, [2, 3])) {
            $data['meetings'] = $this->model->whereNull('course_id')
                ->orderBy('id', 'DESC')
                ->when($user->role_id == 4, function ($q) use ($user) {
                    $q->whereHas('teachers', function ($query) use ($user) {
                        return $query->where('user_id', $user->id);
                    });
                })->where('status', 1)
                ->get();
        }
        $data['records'] = $user->role_id == 2 ? auth()->user()->student->studentRecords : null;
        $data['days'] = SmWeekend::orderby('order')->get(['id', 'name', 'order', 'zoom_order']);
        $data['default_settings'] = ZoomSetting::where('school_id', $user->school_id)->first();
        return $data;
    }

    public function deleteById(int $modelId): bool
    {     
        
        $model = $this->findById($modelId);
        
        if (auth()->user()->role_id != 1) {
            if (auth()->user()->id != $model->created_by) {
                Toastr::error('Meeting is created by other, you could not DELETE !', 'Failed');
                return false;
                return redirect()->back();
            }
        }
        if($model->meeting_id) {
            $token = $this->zoomRepository->createZoomToken();
            $meeting_details = Http::withToken($token)->delete('https://api.zoom.us/v2/meetings/'.$model->meeting_id)->json();
        }

        if (file_exists($model->attached_file)) {
            unlink($model->attached_file);
        }
        $model->delete();
        return true;
    }
}
