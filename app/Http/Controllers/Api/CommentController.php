<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CommentRequest;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Add a new comment or reply
     * @param CommentRequest $commentRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\PostException
     */
    public function store(CommentRequest $commentRequest)
    {
        $commentId = $commentRequest->input('comment_id');
        //
        if (isset($commentId)) {
            $result = $this->commentService->createReply($commentRequest);
        } else {
            $result = $this->commentService->createComment($commentRequest);
        }

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

        $result = $this->commentService->destroy($id);
        return response()->json([
            'code' => '0',
            'info' => $result,
            'msg' => 'ok'
        ]);
    }
}
