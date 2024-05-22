<?php

namespace Modules\WhatsappSupport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agents extends Model
{
    protected $guarded = [];

    protected $table = 'agents';

    public function times()
    {
        return $this->hasMany(AgentTime::class, 'agent_id', 'id');
    }

    public function isAvailable()
    {
        if ($this->always_available) return true;

        if ($this->times->where('day', now()->dayName)->first()) {
            $row = $this->times->where('day', now()->dayName)->first();
            return $row->start_time_str < strtotime(now()->format('H:s:i')) && $row->end_time_str > strtotime(now()->format('H:s:i'));
        }

        return false;
    }
}
