<?php

namespace Modules\Zoom\Repositories\Interfaces;

use App\Repositories\Interfaces\EloquentRepositoryInterface;

interface VirtualClassRepositoryInterface extends EloquentRepositoryInterface
{
    public function index();
    public function classStore($request);
    public function edit(int $id);
    public function show($meeting_id);
    public function classUpdate($request, $id);
}