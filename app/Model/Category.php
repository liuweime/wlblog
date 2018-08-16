<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // 分类对文章是一对多
    public function article()
    {
        return $this->hasMany(Article::class);
    }

    public function childCategory()
    {
        return $this->hasMany(Category::class, 'parent_id','id');
    }

    public function childs()
    {
        return $this->childCategory()->with('childs');
    }
}
