<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CommentRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommitController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
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
     * 添加一个新的评论/回复
     *
     * @param  CommentRequest  $commentRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $commentRequest)
    {
        //

        return response()->json([

        ]);
    }

    /**
     * 获取指定文章下的评论
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return response()->json([

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        return response()->json([

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
        //
        return response()->json([

        ]);
    }
}
