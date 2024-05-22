<?php

namespace Modules\Zoom\Http\Controllers;

use App\SmGeneralSettings;
use App\SmNotification;
use App\SmWeekend;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MacsiDigital\Zoom\Facades\Zoom;
use Modules\RolePermission\Entities\InfixRole;
use Modules\Zoom\Entities\ZoomMeeting;
use Modules\Zoom\Entities\ZoomSetting;
use Modules\Zoom\Http\Requests\VirtualMeetingRequestForm;
use Modules\Zoom\Repositories\Interfaces\VirtualMeetingRepositoryInterface;

class MeetingController extends Controller
{
    protected $virtualMeetingRepository;
    public function __construct(
        VirtualMeetingRepositoryInterface $virtualMeetingRepository
    ) {
        $this->virtualMeetingRepository = $virtualMeetingRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function about()
    {
        return 'ok';
    }
    public function index()
    {
        try {
            $data = $this->virtualMeetingRepository->index();
            return view('zoom::meeting.meeting', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    public function meetingStart($id)
    {
        $time_zone_setup = SmGeneralSettings::join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
            ->where('school_id', Auth::user()->school_id)->first();
        date_default_timezone_set($time_zone_setup->time_zone);
        try {
            $meeting = ZoomMeeting::where('meeting_id', $id)->first();
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
            return redirect($meeting->url);
            // return view('zoom::meeting.meetingStart', $data);
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
    public function store(VirtualMeetingRequestForm $request)
    {
        try {
            //Available time check for classs
            if ($this->isTimeAvailableForMeeting($request, $id = 0)) {
                Toastr::error('Virtual class time is not available for teacher and student!', 'Failed');
                return redirect()->back();
            }

            //Chekc the number of api request by today max limit 100 request
            if (ZoomMeeting::whereDate('created_at', Carbon::now())->count('id') >= 100) {
                Toastr::error('You can not create more than 100 meeting within 24 hour!', 'Failed');
                return redirect()->back();
            }

            $this->virtualMeetingRepository->meetingStore($request);
            Toastr::success('Operation successful', 'Success');
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
            $data = $this->virtualMeetingRepository->show($id);
            if ($data['results']) {
                if (Auth::user()->role_id == 1 || Auth::user()->role_id == 4) {
                    return view('zoom::meeting.meetingDetails', $data);
                } else {
                    return view('zoom::meeting.meetingDetailsStudentParent', $data);
                }
            } else {
                Toastr::error('Operation Failed !', 'Failed');
                return redirect()->back();
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

        try {
            $editData = $this->virtualMeetingRepository->findById($id);
            if (Auth::user()->role_id != 1) {
                if (Auth::user()->id != $editData->created_by) {
                    Toastr::error('Meeting is created by other, you could not modify !', 'Failed');
                    return redirect()->back();
                }
            }
            $data = $this->virtualMeetingRepository->edit($id);
            
            return view('zoom::meeting.meeting', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            if ($this->isTimeAvailableForMeeting($request, $id = $id)) {
                Toastr::error('Virtual class time is not available !', 'Failed');
                return redirect()->back();
            }
            $this->virtualMeetingRepository->meetingUpdate($request, $id);
            Toastr::success('Meeting updated successful', 'Success');
            return redirect()->route('zoom.meetings');

        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */

    public function fileUpload($id)
    {
        try {
            $meeting = ZoomMeeting::findOrFail($id);
            $upload_type = 'meetingUpload';
            return view('zoom::recorder_file_upload', compact('meeting', 'upload_type'));
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function destroy($id)
    {
        try {
            $this->virtualMeetingRepository->deleteById($id);
            Toastr::success('Meeting deleted successful', 'Success');
            return redirect()->route('zoom.meetings');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function userWiseUserList(Request $request)
    {
        if ($request->has('user_type')) {
            $userList = User::where('role_id', $request['user_type'])
                ->where('school_id', Auth::user()->school_id)
                ->select('id', 'full_name', 'school_id')->get();
            return response()->json([
                'users' => $userList,
            ]);
        }
    }
    private function isTimeAvailableForMeeting($request, $id)
    {
        $time_zone_setup = SmGeneralSettings::join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
            ->where('school_id', Auth::user()->school_id)->first();
        date_default_timezone_set($time_zone_setup->time_zone);
        if (isset($request['participate_ids'])) {
            $teacherList = $request['participate_ids'];
        } else {
            $teacherList = [Auth::user()->id];
        }

        if ($id != 0) {
            $meetings = ZoomMeeting::where('date_of_meeting', Carbon::parse($request['date'])->format("m/d/Y"))
                ->where('id', '!=', $id)
                ->where('school_id', Auth::user()->school_id)
                ->whereHas('participates', function ($q) use ($teacherList) {
                    $q->whereIn('user_id', $teacherList);
                })
                ->get();
        } else {
            $meetings = ZoomMeeting::where('date_of_meeting', Carbon::parse($request['date'])->format("m/d/Y"))
                ->where('school_id', Auth::user()->school_id)
                ->whereHas('participates', function ($q) use ($teacherList) {
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
