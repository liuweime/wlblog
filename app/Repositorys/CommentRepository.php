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
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}