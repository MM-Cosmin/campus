<?php

namespace Modules\Zoom\Http\Controllers;

use App\Models\StudentRecord;
use App\SmGeneralSettings;
use App\SmStudent;
use App\SmWeekend;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use MacsiDigital\Zoom\Facades\Zoom;
use Modules\Lms\Entities\LessonComplete;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;
use Modules\Zoom\Entities\VirtualClass;
use Modules\Zoom\Entities\ZoomMeeting;
use Modules\Zoom\Http\Requests\VirtualClassRequest;
use Modules\Zoom\Repositories\Interfaces\VirtualClassRepositoryInterface;

class VirtualClassController extends Controller
{
    protected $virtualClassRepository;
    public function __construct(
        VirtualClassRepositoryInterface $virtualClassRepository
    ) {
        $this->virtualClassRepository = $virtualClassRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        $this->settingCheck();
        $time_zone_setup = SmGeneralSettings::join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
            ->where('school_id', Auth::user()->school_id)->first();
        date_default_timezone_set($time_zone_setup->time_zone);
        try {
            $data = $this->virtualClassRepository->index();
            return view('zoom::virtualClass.meeting', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    public function mychild($id)
    {
        try {
            $data['records'] = StudentRecord::where('student_id', $id)->where('school_id', auth()->user()->school_id)->get();
            return view('zoom::virtualClass.meeting', $data);
        } catch (\Throwable $th) {

        }
    }
    public function meetingStart($id)
    {
        $time_zone_setup = SmGeneralSettings::join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
            ->where('school_id', Auth::user()->school_id)->first();
        date_default_timezone_set($time_zone_setup->time_zone);

        try {
            $meeting = VirtualClass::where('meeting_id', $id)->first();
            if (!$meeting->currentStatus == 'started') {
                Toastr::error('Class not yet start, try later', 'Failed');
                return redirect()->back();
            }
            if (!$meeting->currentStatus == 'closed') {
                Toastr::error('Class are closed', 'Failed');
                return redirect()->back();
            }
            $data['url'] = $meeting->url;
            $data['topic'] = $meeting->topic;
            $data['password'] = $meeting->password;

            if (moduleStatusCheck('Lms')) {
                if (!is_null($meeting->course_id)) {
                    $checkExist = LessonComplete::when(!is_null($meeting->chapter_id), function ($q) use ($meeting) {
                        $q->where('chapter_id', $meeting->chapter_id);
                    })

                        ->when(!is_null($meeting->lesson_id), function ($q) use ($meeting) {
                            $q->where('lesson_id', $meeting->lesson_id);
                        })
                        ->where('virtual_class_id', $meeting->id)
                        ->where('course_id', $meeting->course_id)
                        ->where('student_id', auth()->user()->id)
                        ->first();

                    if (is_null($checkExist)) {
                        $new = new LessonComplete();
                        $new->course_id = $meeting->course_id;
                        $new->chapter_id = $meeting->chapter_id;
                        $new->lesson_id = $meeting->lesson_id;
                        $new->virtual_class_id = $meeting->id;
                        $new->student_id = auth()->user()->id;
                        $new->active_status = 1;
                        $new->save();
                    }
                }
            }

            return redirect($meeting->url);

            // return view('zoom::virtualClass.meetingStart', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(VirtualClassRequest $request)
    {
        try {
            $this->virtualClassRepository->classStore($request);
            Toastr::success('Virtual class created successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $data = $this->virtualClassRepository->show($id);
            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4) {
                return view('zoom::virtualClass.meetingDetails', $data);
            } else {
                return view('zoom::virtualClass.meetingDetailsStudentParent', $data);
            }
            
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $editData = $this->virtualClassRepository->findById($id);
            if (Auth::user()->role_id != 1) {
                if (Auth::user()->id != $editData->created_by) {
                    Toastr::error('Meeting is created by other, you could not modify !', 'Failed');
                    return redirect()->back();
                }
            }
        try {
            $data = $this->virtualClassRepository->edit($id);
            if (moduleStatusCheck('University')) {
                $interface = App::make(UnCommonRepositoryInterface::class);
                $data += $interface->getCommonData($data['editData']);
            }
            return view('zoom::virtualClass.meeting', $data);
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(VirtualClassRequest $request, $id)
    {
        try {
            $system_meeting = VirtualClass::findOrFail($id);

            if ($this->isTimeAvailableForMeeting($request, $id = $id)) {
                Toastr::error('Virtual class time is not available for teacher and student!', 'Failed');
                return redirect()->back();
            }

            $this->virtualClassRepository->classUpdate($request, $id);
            if (moduleStatusCheck('University')) {
                $interface = App::make(UnCommonRepositoryInterface::class);
                $unStore = $interface->storeUniversityData($system_meeting, $request);
                $system_meeting->save();
            }
            Toastr::success('Virtual Class updated successful', 'Success');
            return redirect()->route('zoom.virtual-class');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function fileUpload($id)
    {
        try {
            $meeting = VirtualClass::findOrFail($id);
            $upload_type = 'classUpload';
            return view('zoom::recorder_file_upload', compact('meeting', 'upload_type'));
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function updateVedio(Request $request)
    {

        try {
           
            if ($request->video == null && $request->link == null) {
                Toastr::warning('Fill up at Least one Field', 'Failed');
                return redirect()->back();
            }
            if ($request->meetingupload == 'meetingUpload') {
                $system_meeting = ZoomMeeting::findOrFail($request->meeting_id);
            } elseif ($request->meetingupload == 'classUpload') {
                $system_meeting = VirtualClass::findOrFail($request->meeting_id);
            }

            if ($request->file('video') != "") {
                if (file_exists($system_meeting->local_video)) {
                    unlink($system_meeting->local_video);
                }
                $file = $request->file('video');
                $fileName = $request['topic'] . time() . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/zoom-meeting/', $fileName);
                $fileName = 'public/uploads/zoom-meeting/' . $fileName;
                $system_meeting->local_video = $fileName;
            }
            $system_meeting->vedio_link = $request->link;
            $system_meeting->save();
            Toastr::success('File Upload successful', 'Success');
            if ($request->course_id && moduleStatusCheck('Lms')) {
                Session::forget('course_type');
                return redirect()->route('lms.courseDetail', [$request->course_id, 'virtual_class']);
            } elseif($request->meetingupload == 'meetingUpload') {
                return redirect()->route('zoom.meetings');
            }elseif($request->meetingupload == 'classUpload'){
                return redirect()->route('zoom.virtual-class');
            }else{
                return redirect()->back();
            }

        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
           $data=$this->virtualClassRepository->deleteById($id);
           if($data) {
            Toastr::success('Virtual Class deleted successful', 'Success');
           }
           return redirect()->route('zoom.virtual-class');
        } catch (\Throwable $th) {
            
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    private function settingCheck()
    {
        if (zoomSettings() == false) {
            return redirect()->route('zoom.settings');
        }
    }
    private function checkCondition($request)
    {
        if ($this->isTimeAvailableForMeeting($request, $id = 0)) {
            Toastr::error('Virtual class time is not available for teacher and student!', 'Failed');
            return redirect()->back();
        }

        if (VirtualClass::whereDate('created_at', Carbon::now())->count('id') >= 100) {
            Toastr::error('You can not create more than 100 meeting within 24 hour!', 'Failed');
            return redirect()->back();
        }
    }
    private function isTimeAvailableForMeeting($request, $id)
    {
        if (isset($request['teacher_ids'])) {
            $teacherList = [$request['teacher_ids']];
        } else {
            $teacherList = [Auth::user()->id];
        }

        if ($id != 0) {
            $meetings = VirtualClass::where('date_of_meeting', Carbon::parse($request['date'])->format("m/d/Y"))
                ->where('class_id', $request['class'])
                ->where('id', '!=', $id)
                ->where('section_id', $request['section'])
                ->where('school_id', Auth::user()->school_id)
                ->whereHas('teachers', function ($q) use ($teacherList) {
                    $q->whereIn('user_id', $teacherList);
                })
                ->get();
        } else {
            $meetings = VirtualClass::where('date_of_meeting', Carbon::parse($request['date'])->format("m/d/Y"))
                ->where('class_id', $request['class'])
                ->where('section_id', $request['section'])
                ->where('school_id', Auth::user()->school_id)
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
}
