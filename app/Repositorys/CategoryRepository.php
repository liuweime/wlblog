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
        return is_null(
            $this->category->where('id', $categoryId)->select('id')->first()
        ) ? false : true;
    }
}