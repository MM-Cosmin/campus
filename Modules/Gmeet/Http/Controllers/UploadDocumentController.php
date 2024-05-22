<?php

namespace Modules\Gmeet\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Gmeet\Repositories\Interfaces\UploadDocumentRepositoryInterface;

class UploadDocumentController extends Controller
{
    protected $uploadDocumentRepository;
    public function __construct(
        UploadDocumentRepositoryInterface $uploadDocumentRepository
    ) {
        $this->uploadDocumentRepository = $uploadDocumentRepository;
    }
    public function modal(Request $request, $id)
    {
        $meetingOrClass = $this->uploadDocumentRepository->meetingOrClass($request, $id);
        $type = $request->type;
        return view('gmeet::record_file_upload_modal', compact('meetingOrClass', 'type')); 
    }
    public function store(Request $request)
    {
        try {
            if(!$request->local_video && !$request->video_link) {
                Toastr::error(trans('gmeet::gmeet.Recorded file Upload Failed'), trans('common.error'));
                return redirect()->back();
            }
            $this->uploadDocumentRepository->forVirtualClass($request);
            Toastr::success(trans('gmeet::gmeet.Recorded file Upload Successfully'), trans('common.Success'));
            return $this->getBack($request->type);
        } catch (\Throwable $th) {         
            Toastr::error(trans('gmeet::gmeet.Recorded file Upload Failed'), trans('common.error'));
            return redirect()->back();
        }

    }

    public function destroy(Request $request)
    {
        try {
            $id = $request->id;
            $this->uploadDocumentRepository->deleteRecordedFile($request, $id);

           return $this->getBack($request->type, $id);
        } catch (\Throwable$th) {
            Toastr::error(trans('gmeet::gmeet.Recorded file Delete Failed'), trans('common.Error'));
            return redirect()->back();
        }
    }
    private function getBack($type, $id = null)
    {
        if ($type == 'class') {
            $type = 'g-meet.virtual-class.index';
        } elseif ($type == 'meeting') {
            $type = 'g-meet.virtual-meeting.index';
        } elseif ($type == 'class' && $id) {
            $type = 'g-meet.virtual-class.show'. $id;
        } elseif ($type == 'meeting' && $id) {
            $type = 'g-meet.virtual-meeting.show'. $id;
        } else {
            $type = null;
        }
        if ($type) {
            return redirect()->route($type);
        } 
        return redirect()->back();
    }
}
