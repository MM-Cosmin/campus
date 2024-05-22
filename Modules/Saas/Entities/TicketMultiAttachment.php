<?php

namespace Modules\Saas\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketMultiAttachment extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Saas\Database\factories\TicketMultiAttachmentFactory::new();
    }
}
