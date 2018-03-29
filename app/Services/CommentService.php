<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/3/29
 * Time: 16:56
 */

namespace App\Services;


use App\Http\Requests\CommentRequest;
use App\Http\Requests\ReplyRequest;
use App\Repositorys\CommentRepository;

class CommentService
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function createComment(CommentRequest $commentRequest)
    {

    }

    public function createReply(ReplyRequest $replyRequest)
    {

    }
}