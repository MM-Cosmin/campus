<?php

namespace Modules\Gmeet\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoogleAccount extends Model
{
    use HasFactory;

    protected $fillable = ['google_id', 'name', 'email','token', 'user_id', 'login_at', 'school_id'];
    protected $casts = ['token' => 'json'];
    
    protected static function newFactory()
    {
        return \Modules\Gmeet\Database\factories\GoogleAccountFactory::new();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
