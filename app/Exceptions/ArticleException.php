<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/3/30
 * Time: 16:21
 */

namespace App\Exceptions;

class ArticleException extends LogicException
{
    protected $name = 'article';
    protected $code = '01';
}