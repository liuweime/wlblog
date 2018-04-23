<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    const COMMENT_DELETE = 0;

    const COMMENT_SHOW = 1;

    public function scopePublished($query)
    {
        return $query->where('status', self::COMMENT_SHOW);
    }
}
