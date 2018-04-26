<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    const ARTICLE_DRFAT        = 0;                             // 草稿
    const ARTICLE_PUBLIS       = 1;                             // 发布
    const ARTICLE_AUDIT        = 2;                             // 待审
    const ARTICLE_DELETE       = 3;                             // 已删除

    const ARTICLE_TYPE_ORIGINAL = 0;
    const ARTICLE_TYPE_REPRODUCED = 1;
    const ARTICLE_TYPE_TRANSLATION = 2;

    // 文章与分类是多对一
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 文章对评论是一对多
    public function comment()
    {
        return $this->hasMany(Comment::class, 'tid');
    }

    // 文章对用户是多对一
    public function user()
    {
        return $this->belongsTo(User::class,'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::ARTICLE_PUBLIS);
    }
}
