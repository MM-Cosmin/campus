<?php

namespace Modules\AiContent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiContentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'ai_default_model',
        'ai_default_language',
        'ai_default_tone',
        'ai_max_result_length',
        'ai_default_creativity',
        'open_ai_secret_key',
    ];
}
