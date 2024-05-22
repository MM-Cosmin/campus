<?php

namespace Modules\AppSlider\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppSlider extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\AppSlider\Database\factories\AppSliderFactory::new();
    }
    public function scopeCheckCondition($query)
    {
        if (moduleStatusCheck('Saas') == true && auth()->user()->is_administrator == "yes" && Session::get('isSchoolAdmin') == false && auth()->user()->role_id == 1) {
            return $query->where('school_id', null);
        } else {
            return $query->where('school_id', auth()->user()->school_id);
        }
    }
}
