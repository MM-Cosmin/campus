<?php

namespace Modules\Saas\Entities;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = ['id'];
    public function attachments()
    {
        return $this->hasMany(CommentMultiAttachment::class, 'comment_id', 'id');
    }
}
