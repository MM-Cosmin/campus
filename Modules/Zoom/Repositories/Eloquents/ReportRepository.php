<?php

namespace Modules\Zoom\Repositories\Eloquents;

use Modules\Zoom\Entities\VirtualClass;
use Modules\Zoom\Entities\ZoomMeeting;
use Modules\Zoom\Repositories\Interfaces\ReportRepositoryInterface;

class ReportRepository implements ReportRepositoryInterface
{
    protected $vClass;
    protected $zMeeting;
    public function __construct(
        VirtualClass $vClass,
        ZoomMeeting $zMeeting
    ) {
        $this->vClass = $vClass;
        $this->zMeeting = $zMeeting;
    }
    public function classReports()
    {
        
    }
    public function meetingReports()
    {
        
    }
}
