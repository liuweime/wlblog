<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/5/2
 * Time: 14:34
 */

namespace App\Services;


use App\Repositorys\CategoryRepository;

class CategoryService
{
    private $categoryRepository;
    
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * 获取文章分类
     * @return mixed
     */
    public function getFrontArticleCategory()
    {
        return $this->categoryRepository->getArticleCategories();
    }

    public function getCategoryList()
    {
        return $this->categoryRepository->getAllCategories();
    }
}