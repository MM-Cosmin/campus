<?php

namespace Modules\BBB\Entities;

use Illuminate\Database\Eloquent\Model;

class BbbSetting extends Model
{

    protected static $flushCacheOnUpdate = true;

    protected $guarded = ['id'];
}
