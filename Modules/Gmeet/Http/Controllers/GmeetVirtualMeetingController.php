<?php

namespace Modules\Gmeet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Gmeet\Http\Requests\GmeetVirtualMeetingRequestForm;
use Modules\Gmeet\Repositories\Interfaces\GmeetVirtualMeetingRepositoryInterface;

class GmeetVirtualMeetingController extends Controller
{
    protected $meetingRepository;
    public function __construct(
        GmeetVirtualMeetingRepositoryInterface $meetingRepository
    ) {
        $this->meetingRepository = $meetingRepository;
    }
    public function index()
    {
        $data = $this->meetingRepository->index();
        return view('gmeet::meeting.index', $data);
    }

    public function create()
    {
        return view('gmeet::create');
    }

    public function store(GmeetVirtualMeetingRequestForm $request)
    {
        try {
            $this->meetingRepository->create($request->validated());
            Toastr::success(trans('gmeet::gmeet.Gmeet Virtual Meeting Created Successfully'), trans('common.Success'));
            return redirect()->route('g-meet.virtual-meeting.index');
        } catch (\Throwable $th) {
            Toastr::error(trans('gmeet::gmeet.Gmeet Virtual Meeting Create Failed'), trans('common.Error'));
            return redirect()->route('g-meet.virtual-meeting.index');
        }
    }

    public function show($id)
    {
        $data['virtualMeeting'] = $this->meetingRepository->findById($id);
        return view('gmeet::meeting.show', $data);
    }
    public function userWiseUserList(Request $request)
    {
        $userList = $this->meetingRepository->userWiseUserList($request);
        return response()->json([
            'users' => $userList
        ]);
    }
    public function edit($id)
    {
        $data = $this->meetingRepository->edit($id);
        return view('gmeet::meeting.index', $data);
    }

    public function update(GmeetVirtualMeetingRequestForm $request, $id)
    {
        try {
            $this->meetingRepository->update($id, $request->validated());
            Toastr::success(trans('gmeet::gmeet.Gmeet Virtual Meeting Updated Successfully'), trans('common.Success'));
            return redirect()->route('g-meet.virtual-meeting.index');
        } catch (\Throwable$th) {
            Toastr::error(trans('gmeet::gmeet.Gmeet Virtual Meeting Update Failed'), trans('common.Error'));
            return redirect()->route('g-meet.virtual-meeting.index');
        }
    }

    public function destroy($id)
    {
        try {
            $this->meetingRepository->deleteById($id);
            return redirect()->route('g-meet.virtual-meeting.index');
        } catch (\Throwable$th) {
            Toastr::error(trans('gmeet::gmeet.Gmeet Virtual Meeting Delete Failed'), trans('common.Error'));
            return redirect()->route('g-meet.virtual-meeting.index');
        }
    }
}
