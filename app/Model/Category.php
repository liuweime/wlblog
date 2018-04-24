<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    // 分类对文章是一对多
    public function article()
    {
        return $this->hasMany(Article::class);
    }
}
