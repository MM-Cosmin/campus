<?php

namespace Modules\Gmeet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Modules\Gmeet\Http\Requests\MeetingSearchRequestForm;
use Modules\Gmeet\Repositories\Interfaces\GmeetReportRepositoryInterface;

class GmeetReportController extends Controller
{
    protected $reportRepository;
    public function __construct(
        GmeetReportRepositoryInterface $reportRepository
    ) {
      $this->reportRepository = $reportRepository;  
    }
    public function classReport(Request $request)
    {
        try {
            $data = $this->reportRepository->virtualClass($request);
            return view('gmeet::report.classReports', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }


    public function meetingReport(MeetingSearchRequestForm $request)
    {
        try {
            
            $data = $this->reportRepository->virtualMeeting($request);
            return view('gmeet::report.meetingReports', $data);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

}