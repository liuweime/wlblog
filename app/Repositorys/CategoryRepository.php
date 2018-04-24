<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/3/30
 * Time: 16:24
 */

namespace App\Repositorys;


use App\Model\Category;

class CategoryRepository
{
    /**
     * @var $category Category
     */
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Check category does it exists
     *
     * @param int $categoryId
     * @return bool
     */
    public function isExistsCategoryId(int $categoryId) : bool
    {
        return $this->category->where('id', $categoryId)->exists();
    }

    /**
     * 根据id获取分类名称
     * @param int $categoryId
     * @return mixed
     */
    public function getCategoryNameById(int $categoryId)
    {
        return $this->category->where('id', $categoryId)->select('category_name')->first();
    }
}