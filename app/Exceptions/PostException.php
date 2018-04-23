<?php
/**
 * Created by PhpStorm.
 * User: liu
 * Date: 2018/3/29
 * Time: 21:08
 */

namespace App\Exceptions;

class PostException extends LogicException
{
    protected $code = '03';

    protected $name = 'post';
}