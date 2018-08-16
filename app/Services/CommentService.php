<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/3/29
 * Time: 16:56
 */

namespace App\Services;



use App\Exceptions\PostException;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ReplyRequest;
use App\Http\Resources\PostsCollection;
use App\Repositorys\CommentRepository;

class CommentService
{
    private $commentRepository;

    /**
     * CommentService constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Create a new comment post
     * @param CommentRequest $commentRequest
     * @return \App\Model\Comment
     */
    public function createComment(CommentRequest $commentRequest)
    {
        $comment = $commentRequest->all();
        return $this->commentRepository->createPost($comment);
    }

    /**
     * Create a new reply post
     * @param ReplyRequest $request
     * @return \App\Model\Comment
     * @throws PostException
     */
    public function createReply(ReplyRequest $request)
    {
        // To judge whether the comment exists
        $commentId = $request->input('comment_id');
        if (!$commentId) {
            throw new PostException(['COMMENT_UNSPECIFIED']);
        }
        $bool = $this->commentRepository->isExistCommet($commentId);
        if (false === $bool) {
            throw new PostException(['COMMENT_NOT_FOUND', $commentId]);
        }
        $reply = $request->all();

        return $this->commentRepository->createPost($reply);
    }

    /**
     * 获取指定文章下的评论
     * @param int $article_id
     * @return \App\Model\Comment|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function getComments(int $article_id)
    {
        $commetList = $this->commentRepository->getCommentsByArticleId($article_id);
        if (empty($commetList)) {

            return null;
        }

        //
        return new PostsCollection($commetList);
    }

    public function destroy(int $postId)
    {
        return $this->commentRepository->destroy($postId);
    }
}