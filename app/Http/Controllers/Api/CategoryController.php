<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FrontCategoryCollection;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * 显示文章分类列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $result = $this->categoryService->getFrontArticleCategory();

        return response()->json([
            'code' => 0,
            'info' => new FrontCategoryCollection($result),
            'msg' => 'ok'
        ]);
    }


}
