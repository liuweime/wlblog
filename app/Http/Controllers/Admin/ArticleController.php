<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
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
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {

    }

    public function search(Request $request)
    {
        $filter = $request->all();
        $result = $this->articleService->getArticleList($filter);

        return response()->json([
            'code' => 0,
            'info' => $result,
            'msg' => 'ok'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
