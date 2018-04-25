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
}
