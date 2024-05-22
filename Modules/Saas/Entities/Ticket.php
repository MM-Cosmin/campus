<?php

namespace Modules\Saas\Entities;

use App\SmSchool;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'subject', 'priority_id', 'email', 'assign_user', 'description', 'active_status', 'school_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function agent_user()
    {
        return $this->belongsTo(User::class, 'assign_user', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function attachments()
    {
        return $this->hasMany(TicketMultiAttachment::class, 'ticket_id', 'id');
    }
    public function school()
    {
        return $this->belongsTo(SmSchool::class, 'school_id', 'id')->withDefault();
    }
}
