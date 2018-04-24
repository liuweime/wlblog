<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/3/29
 * Time: 17:03
 */

namespace App\Repositorys;


use App\Model\Comment;

class CommentRepository
{

    /**
     * @var $comment Comment
     */
    private $comment;

    /**
     * CommentRepository constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * 创建一条帖子
     * @param array $comment
     * @return Comment
     */
    public function createPost(array $comment)
    {
        $this->comment->tid = $comment['article_id'];
        $this->comment->nickname = trim($comment['nickname']);
        $this->comment->email = trim($comment['email']);
        $this->comment->content = trim($comment['content']);
        if (isset($comment['comment_id'])) {
            $this->comment->fid = $comment['comment_id'];
        } else {
            $this->comment->fid = 0;
        }

        $this->comment->save();

        return $this->comment;
    }

    /**
     * judge whether the comment exists
     * @param int $commentId
     * @return bool
     */
    public function isExistCommet(int $commentId) : bool
    {
        return $this->comment->where('id', $commentId)->published()->exists();
    }


    /**
     * 获取指定文章下的评论
     * @param int $article_id
     * @return Comment|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function getCommentsByArticleId(int $article_id)
    {
        return $this->comment->where('tid', $article_id)->published()->simplePaginate(5);
    }

    /**
     * 获取指定评论
     * @param int $postId
     * @return mixed|static
     */
    public function getPostByPostId(int $postId)
    {
        return $this->comment->published()->find($postId);
    }

    public function destroy(int $postId)
    {
        $comment = $this->getPostByPostId($postId);
        if (empty($comment)) {

            return true;
        }

        $comment->status = Comment::COMMENT_DELETE;

        return $comment->save();
    }
}