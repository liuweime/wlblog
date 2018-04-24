<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/3/30
 * Time: 16:31
 */

namespace App\Exceptions;

class CategoryException extends LogicException
{
    protected $name = 'category';
    protected $code = '02';
}