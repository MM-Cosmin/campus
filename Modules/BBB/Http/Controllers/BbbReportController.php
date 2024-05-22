<?php

namespace Modules\BBB\Http\Controllers;

use App\Models\StudentRecord;
use App\SmClass;
use App\SmParent;
use App\SmStaff;
use App\SmStudent;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use Modules\BBB\Entities\BbbMeeting;
use Modules\BBB\Entities\BbbVirtualClass;
use Modules\RolePermission\Entities\InfixRole;

class BbbReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            $data['classes'] = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $data['teachers'] = SmStaff::where('active_status', 1)->where(function($q)  {
	                            $q->where('role_id', 4)->orWhere('previous_role_id', 4);
                            })->where('school_id', Auth::user()->school_id)->get();

            if ($request->has('class_id')) {
                if (Auth::user()->role_id == 4) {
                    $data = $this->setSearchKeywordData($data, $request);
                    $data['meetings'] = $this->virtaulClassSearchTeacher($request);
                } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
                    $data = $this->setSearchKeywordData($data, $request);
                    $data['meetings'] = $this->virtaulClassSearchAdmin($request);
                } else {
                    Toastr::error('Your are not authorized!', 'Failed');
                    return redirect()->back();
                }
            }
            return view('bbb::report.report', $data);

        } catch (Exception $e) {

        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function meetingReport(Request $request)
    {
        try {
            $data['roles'] = InfixRole::where(function ($q) {
                $q->where('school_id', Auth::user()->school_id)->orWhere('type', 'System');
            })->whereNotIn('id', [1, 2])->get();
            if ($request->has('member_type')) {
                if (Auth::user()->role_id != 1) {
                    $data['meetings'] = $this->meetingSearchOthers($request);
                } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
                    $data['meetings'] = $this->meetingSearchAdmin($request);
                } else {
                    Toastr::error('Your are not authorized!', 'Failed');
                    return redirect()->back();
                }
                $data = $this->setSearchKeywordDataMeeting($data, $request);
            }
            return view('bbb::report.meeting_reports', $data);

        } catch (Exception $e) {

        }

    }

    private function meetingSearchAdmin($request)
    {
        $from_time = Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
        $to_time = Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

        $query = BbbMeeting::query();
        $query->with('participates');

        if ($request->has('member_ids')) {
            $query->whereHas('participates', function ($qry) use ($request) {
                return $qry->whereIn('user_id', $request['member_ids']);
            });
        }
        if (!$request->has('member_ids')) {
            $UserIDList = User::where('role_id', $request['member_type'])->where('school_id', Auth::user()->school_id)->pluck('id');
            $query->whereHas('participates', function ($qry) use ($UserIDList) {
                return $qry->whereIn('user_id', $UserIDList);
            });
        }

        $query->when($request->has('from_time') && $request['from_time'] != null && $request->has('to_time') && $request['to_time'] != null, function ($q) use ($from_time, $to_time) {
            return $q->whereBetween('start_time', [$from_time, $to_time]);
        });
        return $query->paginate(10);
    }

    private function meetingSearchOthers($request)
    {
        $from_time = Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
        $to_time = Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

        $query = BbbMeeting::query();
        $query->with('participates');

        if ($request->has('member_ids')) {
            $query->whereHas('participates', function ($qry) use ($request) {
                return $qry->whereIn('user_id', $request['member_ids']);
            });
        }

        if (!$request->has('member_ids')) {
            $UserIDList = User::where('role_id', $request['member_type'])->where('school_id', Auth::user()->school_id)->pluck('id');
            $query->whereHas('participates', function ($qry) use ($UserIDList) {
                return $qry->whereIn('user_id', $UserIDList);
            });
        }

        $query->when($request->has('from_time') && $request['from_time'] != null && $request->has('to_time') && $request['to_time'] != null, function ($q) use ($from_time, $to_time) {
            return $q->whereBetween('start_time', [$from_time, $to_time]);
        });
        return $query->get();
    }
    public function create()
    {
        return view('bbb::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    private function virtaulClassSearchAdmin($request)
    {
        $from_time = Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
        $to_time = Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

        $query = BbbVirtualClass::query();
        $query->when($request->has('class_id') && $request['class_id'] != null, function ($q) use ($request) {
            return $q->where('class_id', $request['class_id']);
        });
        $query->when($request->has('section_id') && $request['section_id'] != null, function ($q) use ($request) {
            return $q->where('section_id', $request['section_id']);
        });
        $query->when($request->has('teachser_ids') && $request['teachser_ids'] != null, function ($q) use ($request) {
            $q->whereHas('teachers', function ($qry) use ($request) {
                return $qry->whereIn('user_id', [$request['teachser_ids']]);
            });
        });
        $query->when($request->has('from_time') && $request['from_time'] != null && $request->has('to_time') && $request['to_time'] != null, function ($q) use ($from_time, $to_time) {
            return $q->whereBetween('start_time', [$from_time, $to_time]);
        });
        return $query->get();
    }

    private function virtaulClassSearchTeacher($request)
    {
        $from_time = Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
        $to_time = Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

        $query = BbbVirtualClass::query();
        $query->when($request->has('class_id') && $request['class_id'] != null, function ($q) use ($request) {
            return $q->where('class_id', $request['class_id']);
        });
        $query->when($request->has('section_id') && $request['section_id'] != null, function ($q) use ($request) {
            return $q->where('section_id', $request['section_id']);
        });
        $query->when($request->has('teachser_ids') && $request['teachser_ids'] != null, function ($q) {
            $q->whereHas('teachers', function ($qry) {
                return $qry->where('user_id', Auth::user()->id);
            });
        });
        $query->when($request->has('from_time') && $request['from_time'] != null && $request->has('to_time') && $request['to_time'] != null, function ($q) use ($from_time, $to_time) {
            return $q->whereBetween('start_time', [$from_time, $to_time]);
        });
        return $query->get();
    }

    private function setSearchKeywordData($data, $request)
    {
        $data['class_id'] = $request['class_id'];
        $data['section_id'] = $request['section_id'];
        $data['teachser_ids'] = $request['teachser_ids'];
        $data['from_time'] = $request['from_time'];
        $data['to_time'] = $request['to_time'];
        return $data;
    }

    private function setSearchKeywordDataMeeting($data, $request)
    {
        $data['member_type'] = $request['member_type'];
        $data['member_ids'] = $request['member_ids'];
        $data['from_time'] = $request['from_time'];
        $data['to_time'] = $request['to_time'];
        return $data;
    }
    public function store(Request $request)
    {
        //
    }

    public function recordingList()
    {
        $role_id = Auth::user()->role_id;
        $records = [];

        if (Auth::user()->role_id == 4) {

            $all_meeting = BbbVirtualClass::orderBy('id', 'DESC')->whereHas('teachers', function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })->pluck('meeting_id')->toArray();
        } elseif (Auth::user()->role_id == 1 || Auth::user()->role_id == 5) {
            $all_meeting = BbbVirtualClass::orderBy('id', 'DESC')->pluck('meeting_id')->toArray();
        } elseif (Auth::user()->role_id == 3) {

            $parent = SmParent::where('user_id', Auth::user()->id)->first();
            $all_meeting = BbbVirtualClass::orderBy('id', 'DESC')->with('section', 'section.students')->whereHas('section', function ($query) use ($parent) {
                return $query->whereHas('students', function ($query) use ($parent) {
                    return $query->where('sm_students.parent_id', $parent->id);
                });
            })->pluck('meeting_id')->toArray();

        } elseif (Auth::user()->role_id == 2) {
            $records = StudentRecord::where('student_id', auth()->user()->student->id)
            ->where('school_id', auth()->user()->school_id)
            ->where('academic_id', getAcademicId())->get();

            $student = SmStudent::where('user_id', Auth::user()->id)->first();

            $all_meeting = BbbVirtualClass::orderBy('id', 'DESC')->pluck('meeting_id')->toArray();
        } else {

            $all_meeting = BbbVirtualClass::orderBy('id', 'DESC')->with('section', 'section.students')->whereHas('section', function ($query) {
                return $query->whereHas('students', function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                });
            })->pluck('meeting_id')->toArray();
        }

        $meeting_id = $all_meeting;

       if(empty($meeting_id)) {
        $recorList = collect();
       } else {

            $recorList = Bigbluebutton::getRecordings([
                'meetingID' => $meeting_id,
                //'meetingID' => ['tamku','xyz'], //pass as array if get multiple recordings
                //'recordID' => 'a3f1s',
                //'recordID' => ['xyz.1','pqr.1'] //pass as array note :If a recordID is specified, the meetingID is ignored.
                // 'state' => 'any' // It can be a set of states separate by commas
            ]);
            //  return $recorList;
        }
        return view('bbb::report.class_record_list', compact('recorList', 'records'));

    }
    public function myChild($id)
    {
        $records = StudentRecord::where('student_id', $id)
        ->where('school_id', auth()->user()->school_id)
        ->where('academic_id', getAcademicId())
        ->get();
        $student = SmStudent::where('id', $id)->first();
        $class_id = $student->class_id;
        $section_id = $student->section_id;
        $all_meeting = BbbVirtualClass::orderBy('id', 'DESC')
            ->where('class_id', $class_id)
            ->where('section_id', $section_id)
            ->orwhere('section_id', null)
            ->get();

        $meeting_id = [];

        foreach ($all_meeting as $m_ids) {
            $meeting_id[] = $m_ids->meeting_id;
        }

        $recorList = Bigbluebutton::getRecordings([
            'meetingID' => $meeting_id,

        ]);

        return view('bbb::report.class_record_list', compact('recorList','records'));
    }
    public function recordingMeetingList()
    {

        if (Auth::user()->role_id == 4) {

            $all_meeting = BbbMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })->orWhere('created_by', Auth::user()->id)->pluck('meeting_id')->toArray();
        } elseif (Auth::user()->role_id == 1) {
            $all_meeting = BbbMeeting::orderBy('id', 'DESC')->pluck('meeting_id')->toArray();
        } else {
            $all_meeting = BbbMeeting::orderBy('id', 'DESC')->whereHas('participates', function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })->pluck('meeting_id')->toArray();
        }

        $meeting_id = $all_meeting;

        if(empty($meeting_id)) {
            $recorList = collect();
        } else {
            $recorList = Bigbluebutton::getRecordings(
                [
                'meetingID' => $meeting_id,
                //'meetingID' => ['tamku','xyz'], //pass as array if get multiple recordings
                //'recordID' => 'a3f1s',
                //'recordID' => ['xyz.1','pqr.1'] //pass as array note :If a recordID is specified, the meetingID is ignored.
                // 'state' => 'any' // It can be a set of states separate by commas
            ]);
        }
        return view('bbb::report.meeting_record_list', compact('recorList'));

    }

}
