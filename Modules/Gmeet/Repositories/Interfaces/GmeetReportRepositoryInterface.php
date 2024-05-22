<?php

namespace Modules\Gmeet\Repositories\Interfaces;

interface GmeetReportRepositoryInterface
{
    public function virtualClass($request):array;
    public function virtualMeeting($request):array;
}
