<?php

namespace Modules\Gmeet\Repositories\Eloquents;

use App\SmClass;
use App\SmStaff;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Modules\Gmeet\Entities\GmeetVirtualClass;
use Modules\Gmeet\Entities\GmeetVirtualMeeting;
use Modules\Gmeet\Repositories\Interfaces\GmeetReportRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\GmeetVirtualClassRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\GmeetVirtualMeetingRepositoryInterface;
use Modules\RolePermission\Entities\InfixRole;

class GmeetReportRepository implements GmeetReportRepositoryInterface
{
    protected $virtualClassRepository;
    protected $virtualMeetingRepository;
    public function __construct(
        GmeetVirtualClassRepositoryInterface $virtualClassRepository,
        GmeetVirtualMeetingRepositoryInterface $virtualMeetingRepository
    ) {
        $this->virtualClassRepository = $virtualClassRepository;
        $this->virtualMeetingRepository = $virtualMeetingRepository;
    }

    public function virtualClass($request): array
    {

        $data['classes'] = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', auth()->user()->school_id)->get();
        $data['teachers'] = SmStaff::where('active_status', 1)->where(function($q)  {
	$q->where('role_id', 4)->orWhere('previous_role_id', 4);})->where('school_id', auth()->user()->school_id)->get();

        if ($request->has('class_id')) {
            if (auth()->user()->role_id == 4) {
                $data = $this->setSearchKeywordData($data, $request);
                $data['meetings'] = $this->virtualClassSearchTeacher($request);
            } elseif (auth()->user()->role_id == 1) {
                $data = $this->setSearchKeywordData($data, $request);
                $data['meetings'] = $this->virtualClassSearchAdmin($request);
            } else {
                Toastr::error('Your are not authorized!', 'Failed');
                return redirect()->back()->send();
            }
        }
        return $data;
    }

    public function virtualMeeting($request): array
    {

        $data['roles'] = InfixRole::where(function ($q) {
            $q->where('school_id', auth()->user()->school_id)->orWhere('type', 'System');
        })->whereNotIn('id', [1, 2])->get();
 
        if (auth()->user()->role_id != 1) {
            $data['meetings'] = $this->meetingSearchOthers($request);
        } elseif (auth()->user()->role_id == 1) {
            $data['meetings'] = $this->meetingSearchAdmin($request);
        } else {
            Toastr::error('Your are not authorized!', 'Failed');
            return redirect()->back();
        }
        $data += $this->setSearchKeywordDataMeeting($data, $request);

        return $data;

    }

    private function meetingSearchAdmin($request)
    {
        $from_time = Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
        $to_time = Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

        $query = GmeetVirtualMeeting::query();
        $query->with('participates');

        if ($request->has('member_ids')) {
            $query->whereHas('participates', function ($qry) use ($request) {
                return $qry->whereIn('user_id', $request['member_ids']);
            });
        }
        if (!$request->has('member_ids') && $request['member_type']) {
            $UserIDList = User::where('role_id', $request['member_type'])->where('school_id', auth()->user()->school_id)->pluck('id');
            
            $query->whereHas('participates', function ($qry) use ($UserIDList) {
                return $qry->whereIn('user_id', $UserIDList);
            });
        }elseif(!$request->has('member_ids') && !$request['member_type'] && auth()->user()->role_id ==1){
             $UserIDList = User::whereNotIn('role_id', [2])->where('school_id', auth()->user()->school_id)->pluck('id');
            
            $query->whereHas('participates', function ($qry) use ($UserIDList) {
                return $qry->whereIn('user_id', $UserIDList);
            });
        }else {
             $UserIDList = User::where('role_id', auth()->user()->role_id)->where('school_id', auth()->user()->school_id)->pluck('id');
            
            $query->whereHas('participates', function ($qry) use ($UserIDList) {
                return $qry->whereIn('user_id', $UserIDList);
            });
        }
        $query->when($request->has('teachser_ids') && $request['teachser_ids'] != null, function ($q) use ($request) {
            $q->whereHas('participates', function ($qry) use ($request) {
                return $qry->whereIn('user_id', [$request['teachser_ids']]);
            });
        });
        $query->when($request->has('from_time') && $request['from_time'] != null && $request->has('to_time') && $request['to_time'] != null, function ($q) use ($from_time, $to_time) {
            return $q->whereBetween('start_time', [$from_time, $to_time]);
        });
        return $query->get();
    }

    private function meetingSearchOthers($request)
    {
        $from_time = Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
        $to_time = Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

        $query = GmeetVirtualMeeting::query();
        $query->with('participates');

        if ($request->has('member_ids')) {
            $query->whereHas('participates', function ($qry) use ($request) {
                return $qry->whereIn('user_id', $request['member_ids']);
            });
        }

        if (!$request->has('member_ids')) {
            $UserIDList = User::where('role_id', $request['member_type'])->where('school_id', auth()->user()->school_id)->pluck('id');
           
            $query->whereHas('participates', function ($qry) use ($UserIDList) {
                return $qry->whereIn('user_id', $UserIDList);
            });
        }
        $query->when($request->has('teachser_ids') && $request['teachser_ids'] != null, function ($q) use ($request) {
            $q->whereHas('participates', function ($qry) use ($request) {
                return $qry->whereIn('user_id', [$request['teachser_ids']]);
            });
        });
        $query->when($request->has('from_time') && $request['from_time'] != null && $request->has('to_time') && $request['to_time'] != null, function ($q) use ($from_time, $to_time) {
            return $q->whereBetween('start_time', [$from_time, $to_time]);
        });
        return $query->get();
    }

    private function virtualClassSearchAdmin($request)
    {
        $from_time = Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
        $to_time = Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

        $query = GmeetVirtualClass::query();
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

    private function virtualClassSearchTeacher($request)
    {
        $from_time = Carbon::parse($request['from_time'])->startOfDay()->toDateTimeString();
        $to_time = Carbon::parse($request['to_time'])->endOfDay()->toDateTimeString();

        $query = GmeetVirtualClass::query();
        $query->when($request->has('class_id') && $request['class_id'] != null, function ($q) use ($request) {
            return $q->where('class_id', $request['class_id']);
        });
        $query->when($request->has('section_id') && $request['section_id'] != null, function ($q) use ($request) {
            return $q->where('section_id', $request['section_id']);
        });
        $query->when($request->has('teachser_ids') && $request['teachser_ids'] != null, function ($q) {
            $q->whereHas('teachers', function ($qry) {
                return $qry->where('user_id', auth()->user()->id);
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

}
