<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\FrontArticleCollection;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{

    /**
     * @var $articleService ArticleService
     */
    private $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * 归档文章列表
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function archive()
    {
        $result = $this->articleService->getArchiveArticle();

        return response()->json([
            'code' => 0,
            'info' => new FrontArticleCollection($result),
            'msg' => 'ok'
        ]);
    }

    /**
     * 首页文章
     *
     * @return ArticleCollection
     * @throws \Exception
     */
    public function index()
    {
        //
        $result = $this->articleService->getFrontArticle();

        return response()->json([
            'code' => 0,
            'info' => new FrontArticleCollection($result),
            'msg' => 'ok'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return ArticleResource
     * @throws \App\Exceptions\ArticleException
     */
    public function show(int $id)
    {
        $result = $this->articleService->article($id);

        return response()->json([
            'code' => 0,
            'info' => new ArticleResource($result),
            'msg' => 'ok'
        ]);
    }

    /**
     * 获取指定分类下文章
     *
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\CategoryException
     */
    public function classifiedArticle(string $name)
    {
        $result = $this->articleService->getArticleByCategoryName($name);

        return response()->json([
            'code' => 0,
            'info' => new FrontArticleCollection($result),
            'msg' => 'ok'
        ]);
    }

    /**
     * 获取指定标签下文章
     *
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function tagArticle(string $name)
    {
        $result = $this->articleService->getArticleByTagName($name);

        return response()->json([
            'code' => 0,
            'info' => new FrontArticleCollection($result),
            'msg' => 'ok'
        ]);
    }
}
