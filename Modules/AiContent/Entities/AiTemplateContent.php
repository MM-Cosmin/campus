<?php

namespace Modules\AiContent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiTemplateContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'content',
    ];
    protected $table = 'ai_template_contents';

    protected $casts = [
        'content' => 'array',
    ];

    public function template()
    {
        return $this->belongsTo(AiTemplate::class, 'template_id');
    }
}
