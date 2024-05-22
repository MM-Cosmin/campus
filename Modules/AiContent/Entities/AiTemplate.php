<?php

namespace Modules\AiContent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'type',
        'status',
        'created_by',
    ];
    protected $table = 'ai_templates';
    public function template_content()
    {
        return $this->belongsTo(AiTemplateContent::class, 'id', 'template_id');
    }
}
