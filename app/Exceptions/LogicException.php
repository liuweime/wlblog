<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/4/4
 * Time: 16:18
 */

namespace App\Exceptions;


use Exception;
use Illuminate\Http\Request;

class LogicException extends Exception
{
    protected $name = 'logic';

    protected $code = '00';

    public function __construct(array $message)
    {
        // 读取配置
        $exceptionName = $this->name;
        $exceptionConfig = config('exception.' . $exceptionName);

        // 读取错误信息
        $info = array_shift($message);
        $format = '';
        if (!empty($message)) {
            $format = $message;
        }
        $info = $exceptionConfig[$info];
        // 格式化
        $msg = sprintf($info[1], $format);

        parent::__construct($msg, $this->code . $info[0]);
    }

    public function report()
    {

    }

    public function render(Request $request)
    {
        $message = $this->getMessage();
        $code = $this->getCode();

        if ($request->ajax()) {
            return response()->json([
                'code' => $code,
                'msg' => $message,
                'info' => null
            ]);
        }
    }
}