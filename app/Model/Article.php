<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    const ARTICLE_DRFAT        = 0;                             // 草稿
    const ARTICLE_PUBLIS       = 1;                             // 发布
    const ARTICLE_AUDIT        = 2;                             // 待审
    const ARTICLE_DELETE       = 3;                             // 已删除
    const ARTICLE_SAVE_DRFAT   = 4;                             // 保存草稿
    const ARTICLE_SAVE_PUBLISH = 5;                             // 保存发布
    const ARTICLE_SAVE_AUDIT   = 6;                             // 保存待审

    const ARTICLE_TYPE_ORIGINAL = 0;
    const ARTICLE_TYPE_REPRODUCED = 1;
    const ARTICLE_TYPE_TRANSLATION = 2;

    protected $guarded    = [];
}
