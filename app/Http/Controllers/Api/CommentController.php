<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\ReplyRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Add a new comment or reply
     * @param CommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CommentRequest $request)
    {
        $result = $this->commentService->createComment($request);

        return response()->json([
            'code' => '0',
            'info' => $result,
            'msg' => 'ok'
        ]);
    }

    /**
     * 添加文章回复
     * @param ReplyRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\PostException
     */
    public function reply(ReplyRequest $request)
    {
        $result = $this->commentService->createReply($request);

        return response()->json([
            'code' => '0',
            'info' => $result,
            'msg' => 'ok'
        ]);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        //
        $result = $this->commentService->getComments($id);

        return response()->json([
            'code' => '0',
            'info' => $result,
            'msg' => 'ok'
        ]);
    }
}
