<?php

namespace Modules\ParentRegistration\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmStudentField extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\ParentRegistration\Database\factories\SmStudentFieldFactory::new();
    }
}
