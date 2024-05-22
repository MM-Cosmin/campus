<?php

namespace Modules\WhatsappSupport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    protected $guarded = [];

    protected $table = 'messages';
}
