<?php

namespace App;

use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmBankAccount extends Model
{

    protected $casts = [
        'opening_balance' => 'double',
        'current_balance' => 'double',
        'active_status' => 'integer',
        'school_id' => 'integer',
        'academic_id' => 'integer'
    ];


    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new ActiveStatusSchoolScope);
    }
    use HasFactory;
}
