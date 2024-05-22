<?php

namespace Modules\InAppLiveClass\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\VirtualClass\Entities\VirtualClass;

class InAppLiveClassMeeting extends Model
{
    protected $guarded = [];

    public function class()
    {
        return $this->belongsTo(VirtualClass::class, 'class_id')->withDefault();
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id')->withDefault();
    }


}
