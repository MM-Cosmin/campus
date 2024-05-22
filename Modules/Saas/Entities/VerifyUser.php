<?php

namespace Modules\Saas\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class VerifyUser extends Model
{
   
    protected $fillable = [
        'user_id', 'token'
    ];

    public function user()
    {
        $user=User::all();
        return $this->belongsTo('App\User', 'user_id');
    }
}
