<?php

namespace Modules\Gmeet\Entities;

use App\User;
use App\SmClass;
use App\SmSection;
use Carbon\Carbon;
use App\SmGeneralSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GmeetVirtualClass extends Model
{
    use HasFactory;

    protected $table = 'gmeet_virtual_classes';
    protected $guarded = ['id'];
    protected $dates = ['end_time'];
    protected static function newFactory()
    {
        return \Modules\Gmeet\Database\factories\GmeetVirtualClassFactory::new();
    }


    public function teachers():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'gmeet_virtual_class_teachers', 'meeting_id', 'user_id');
    }
    public function getTeachersNameAttribute()
    {
        return implode(', ', $this->teachers->pluck('full_name')->toArray());
    }
    public function class():BelongsTo
    {
        return $this->belongsTo(SmClass::class, 'class_id', 'id')->withDefault();
    }
    public function section():BelongsTo
    {
        return $this->belongsTo(SmSection::class, 'section_id', 'id')->withDefault(['section_name'=>'All Sections']);
    }
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }
    public function getMeetingDateTimeAttribute()
    {
        return Carbon::parse($this->date_of_meeting . ' ' . $this->time_of_meeting)->format('m-d-Y h:i A');
    }

    public function getCurrentStatusAttribute()
    {
        $GSetting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
         date_default_timezone_set($GSetting->timeZone->time_zone);
         
        $now = Carbon::now()->setTimezone($GSetting->timeZone->time_zone);

        $meeting_start=$this->time_before_start ?? 10;       

        if ($this->is_recurring == 1) {
            if ($now->between(Carbon::parse($this->start_time)->addMinute(-$meeting_start)->format('Y-m-d H:i:s'), Carbon::parse($this->recurring_end_date)->endOfDay()->format('Y-m-d H:i:s'))) {
                return 'started';
            }
            if (!$now->gt(Carbon::parse($this->recurring_end_date)->addMinute(-$meeting_start))) {
                return 'waiting';
            }
            return 'closed';
        } else {
            if ($now->between(Carbon::parse($this->start_time)->addMinute(-$meeting_start)->format('Y-m-d H:i:s'), Carbon::parse($this->end_time)->format('Y-m-d H:i:s'))) {
                return 'started';
            }

            if (!$now->gt(Carbon::parse($this->end_time)->addMinute(-$meeting_start))) {
                return 'waiting';
            }
            return 'closed';
        }
    }

    public function getMeetingEndTimeAttribute()
    {
        return Carbon::parse($this->date_of_meeting . ' ' . $this->time_of_meeting)->addMinute($this->meeting_duration);
    }
}
