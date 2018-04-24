<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    const COMMENT_DELETE = 0;

    const COMMENT_SHOW = 1;
    //
    // 评论对文章是多对一
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function scopeShowed($query)
    {
        return $query->where('status', self::COMMENT_SHOW);
    }
}