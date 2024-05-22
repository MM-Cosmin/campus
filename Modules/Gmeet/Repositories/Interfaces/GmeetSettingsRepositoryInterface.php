<?php

namespace Modules\Gmeet\Repositories\Interfaces;

use App\Repositories\Interfaces\EloquentRepositoryInterface;

interface GmeetSettingsRepositoryInterface extends EloquentRepositoryInterface
{
    public function index():array;
    public function gSetting();
}
