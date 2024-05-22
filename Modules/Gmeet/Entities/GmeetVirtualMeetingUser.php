<?php

namespace Modules\Gmeet\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GmeetVirtualMeetingUser extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Gmeet\Database\factories\GmeetVirtualMeetingUserFactory::new();
    }
}
