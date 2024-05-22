<?php

namespace Modules\Zoom\Repositories\Interfaces;

use App\Repositories\Interfaces\EloquentRepositoryInterface;

interface VirtualMeetingRepositoryInterface extends EloquentRepositoryInterface
{
    public function index();
    public function show($id);
    public function edit(int $id);
    public function meetingUpdate($request, $id);
    public function meetingStore($request);
}
