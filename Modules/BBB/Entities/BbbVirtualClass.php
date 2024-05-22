<?php

namespace Modules\BBB\Entities;
use App\User;
use App\SmClass;
use App\SmStaff;
use App\SmSection;
use Carbon\Carbon;
use App\SmGeneralSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

class BbbVirtualClass extends Model
{
    protected $table = 'bbb_virtual_classes';
    protected $guarded = ['id'];
    protected $dates = ['end_time'];
    protected static $flushCacheOnUpdate = true; 


    public function teachers()
    {
        return $this->belongsToMany(User::class, 'bbb_virtual_class_teachers', 'meeting_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }

    public function class()
    {
        return $this->hasOne(SmClass::class, 'id', 'class_id')->withDefault();
    }

    public static function classSection($meeting_id)
    {
        return BbbVirtualClass::where('meeting_id', $meeting_id)->first();
    }

    public function section()
    {
        return $this->hasOne(SmSection::class, 'id', 'section_id')->withDefault();
    }

    public function getTeachersNameAttribute()
    {
        return implode(', ', $this->teachers->pluck('full_name')->toArray());
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

        if($this->time_start_before==null){
            $before_start=10;
        }else{
            $before_start=$this->time_start_before;
        }

        if ($this->is_recurring == 1) {
            if ($now->between(Carbon::parse($this->start_time)->addMinute(-$before_start)->format('Y-m-d H:i:s'), Carbon::parse($this->recurring_end_date)->endOfDay()->format('Y-m-d H:i:s'))) {
                return 'started';
                // $today = Carbon::now()->setTimezone($GSetting->timeZone->time_zone)->format('Y-m-d');
                // $start_time = Carbon::parse($today.' '. date("H:i:s", strtotime($this->time_of_meeting)))->addMinute(-10)->setTimezone($GSetting->timeZone->time_zone)->format('Y-m-d H:i:s');
                // $end_time = Carbon::parse($today.' '. date("H:i:s", strtotime($this->time_of_meeting)))->setTimezone($GSetting->timeZone->time_zone)->addMinute($this->meeting_duration + 10 )->format('Y-m-d H:i:s');
                // return  $this->end_time;
                // if($now->between($start_time,$end_time)){
                //     return 'started';
                // }

                // return '00';
                // return 'waiting';
            }
            if (!$now->gt(Carbon::parse($this->recurring_end_date)->addMinute(-$before_start))) {
                return 'waiting';
            }
            return 'closed';
        } else {
            if ($now->between(Carbon::parse($this->start_time)->addMinute(-$before_start)->format('Y-m-d H:i:s'), Carbon::parse($this->end_time)->format('Y-m-d H:i:s'))) {
                return 'started';
            }

            if (!$now->gt(Carbon::parse($this->end_time)->addMinute(-$before_start))) {
                return 'waiting';
            }
            return 'closed';
        }
    }

    public function getMeetingEndTimeAttribute()
    {
        return Carbon::parse($this->date_of_meeting . ' ' . $this->time_of_meeting)->addMinute($this->meeting_duration);
    }

    public function getUrlAttribute()
    {
        if (Auth::user()->role_id == 4 || Auth::user()->role_id == 1) {
            return 'https://zoom.us/wc/' . $this->meeting_id . '/start';
        } else {
            return 'https://zoom.us/wc/' . $this->meeting_id . '/join';
        }
    }
}
