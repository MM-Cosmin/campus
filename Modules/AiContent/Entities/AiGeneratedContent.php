<?php

namespace Modules\AiContent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiGeneratedContent extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function template()
    {
        return $this->belongsTo(AiTemplate::class, 'template_id', 'id')->withDefault();
    }
}
