<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ArticleRequest;
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
     */
    public function archive()
    {
        $result = $this->articleService->getArticleList();

        return response()->json([
            'code' => 0,
            'info' => $result,
            'msg' => 'ok'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\ArticleException
     */
    public function show($id)
    {
        $result = $this->articleService->getArticle($id);

        return response()->json([
            'code' => 0,
            'info' => $result,
            'msg' => 'ok'
        ]);
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
    public function destroy($id)
    {
        $result = $this->articleService->deleteArticle($id);

        return response()->json([
            'code' => 0,
            'info' => $result,
            'msg' => 'ok'
        ]);
    }
}
