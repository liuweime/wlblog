<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Services\ArticleService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
     * 搜索文章列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function search(Request $request)
    {
        $filter = $request->all();
        $result = $this->articleService->getArticleListByFilter($filter, 20);

        return response()->json([
            'code' => 0,
            'info' => new ArticleCollection($result),
            'msg' => 'ok'
        ]);
    }

    /**
     * 文章创建
     * @param ArticleRequest $articleRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ArticleException
     * @throws \App\Exceptions\CategoryException
     * @throws \Exception
     */
    public function store(ArticleRequest $articleRequest)
    {
        $result = $this->articleService->createArticle($articleRequest);

        return response()->json([
            'code' => 0,
            'info' => $result,
            'msg' => 'ok'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ArticleRequest $articleRequest
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ArticleException
     * @throws \App\Exceptions\CategoryException
     * @throws \Exception
     */
    public function update(ArticleRequest $articleRequest, $id)
    {
        $result = $this->articleService->saveArticle($articleRequest, $id);

        return response()->json([
            'code' => 0,
            'info' => $result,
            'msg' => 'ok'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $result = $this->articleService->deleteArticle($id);

        return response()->json([
            'code' => 0,
            'info' => $result,
            'msg' => 'ok'
        ]);
    }
}
