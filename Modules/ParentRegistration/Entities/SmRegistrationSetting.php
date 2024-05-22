<?php

namespace Modules\ParentRegistration\Entities;

use Illuminate\Database\Eloquent\Model;

class SmRegistrationSetting extends Model
{
    protected $fillable = [];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function leadCity()
    {
        if (moduleStatusCheck('Lead') == true) {
            return $this->belongsTo('Modules\Lead\Entities\LeadCity', 'lead_city_id', 'id')->withDefault();
        }  
    }
    public function source()
    {
        if (moduleStatusCheck('Lead') == true) {
            return $this->belongsTo('Modules\Lead\Entities\Source', 'source_id', 'id')->withDefault();
        }  
    }
}
