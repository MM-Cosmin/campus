<?php

namespace Modules\WhatsappSupport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentTime extends Model
{
    protected $guarded = [];

    protected $table = 'agent_times';

    protected $appends = ['start_time_str', 'end_time_str'];

    public function getStartTimeStrAttribute()
    {
        return strtotime($this->start);
    }

    public function getEndTimeStrAttribute()
    {
        return strtotime($this->end);
    }

    public function agent()
    {
        return $this->belongsTo(Agents::class, 'agent_id');
    }
}
