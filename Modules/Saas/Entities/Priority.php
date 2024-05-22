<?php

namespace Modules\Saas\Entities;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $fillable =[
        'name','active_status'
    ];
}
