<?php

namespace Modules\Gmeet\Repositories\Interfaces;

use App\Repositories\Interfaces\EloquentRepositoryInterface;

interface GmeetVirtualMeetingRepositoryInterface extends EloquentRepositoryInterface

{
    public function index():array;
    public function edit(int $modelId):array;
    public function userWiseUserList($request);
}
