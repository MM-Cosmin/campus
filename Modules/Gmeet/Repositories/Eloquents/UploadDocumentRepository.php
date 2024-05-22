<?php

namespace Modules\Gmeet\Repositories\Eloquents;

use Brian2694\Toastr\Facades\Toastr;
use Modules\Gmeet\Entities\GmeetVirtualClass;
use Modules\Gmeet\Entities\GmeetVirtualMeeting;
use Modules\Gmeet\Repositories\Interfaces\GmeetVirtualClassRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\GmeetVirtualMeetingRepositoryInterface;
use Modules\Gmeet\Repositories\Interfaces\UploadDocumentRepositoryInterface;

class UploadDocumentRepository implements UploadDocumentRepositoryInterface
{
    protected $class;
    protected $meeting;
    protected $virtualClassRepository;
    protected $virtualMeetingRepository;
    public function __construct(
        GmeetVirtualClass $class,
        GmeetVirtualMeeting $meeting,
        GmeetVirtualClassRepositoryInterface $virtualClassRepository,
        GmeetVirtualMeetingRepositoryInterface $virtualMeetingRepository
    ) {
        $this->class = $class;
        $this->meeting = $meeting;
        $this->virtualClassRepository = $virtualClassRepository;
        $this->virtualMeetingRepository = $virtualMeetingRepository;
    }
    public function meetingOrClass($request, int $id)
    {
        $data = null;
        if ($request->type == 'class') {
            $data = $this->virtualClassRepository->findById($id);
        } elseif ($request->type == 'meeting') {
            $data = $this->virtualMeetingRepository->findById($id);
        } else {
            Toastr::error(trans('common.Operation Failed'), trans('common.Error'));
            return redirect()->back()->send();
        }
        return $data;
    }
    public function forVirtualClass($request)
    {
        $path = 'public/uploads/gmeet/';
        $file = $request->local_video;
        $id = $request->id;
        if ($request->type == 'class' && $id) {
            $path = 'public/uploads/gmeet/';
            $file = $request->local_video;
            $model = $this->virtualClassRepository->findById($id);
            $model->video_link = $request->video_link;
            $model->local_video = fileUpload($file, $path);
            $model->save();
        } elseif ($request->type == 'meeting' && $id) {
            $this->forVirtualMeeting($request);
        }
    }

    public function forVirtualMeeting($request)
    {
        $path = 'public/uploads/gmeet/';
        $file = $request->local_video;
        $model = $this->virtualMeetingRepository->findById($request->id);
        $model->video_link = $request->video_link;
        $model->local_video = fileUpload($file, $path);
        $model->save();
    }
    public function deleteRecordedFile($request, int $id): bool
    {

        if ($request->type == 'class') {
            $class = $this->virtualClassRepository->findById($id);
            $class->update([
                'local_video' => null,
            ]);
            if ($class->local_video) {
                unlink($class->local_video);
            }
            Toastr::success(trans('gmeet::gmeet.Recorded file Removed Successfully'), trans('common.Success'));
            return true;
        } elseif ($request->type == 'meeting') {
            $meeting = $this->virtualMeetingRepository->findById($id);
            $meeting->update([
                'local_video' => null,
            ]);
            if ($meeting->local_video) {
                unlink($meeting->local_video);
            }
            Toastr::success(trans('gmeet::gmeet.Recorded file Removed Successfully'), trans('common.Success'));
            return true;
        } else {
            Toastr::error(trans('common.Operation Failed'), trans('common.Error'));
            
            return false;
        }

    }
}
