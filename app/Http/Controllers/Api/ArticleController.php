<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
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
        $result = $this->articleService->getArticleList();

        return response()->json([
            'code' => 0,
            'info' => new ArticleCollection($result),
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
        $result = $this->articleService->getArticleList([], true);

        return response()->json([
            'code' => 0,
            'info' => new ArticleCollection($result),
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
        $result = $this->articleService->getArticle($id);

        return response()->json([
            'code' => 0,
            'info' => new ArticleResource($result),
            'msg' => 'ok'
        ]);
    }
}
