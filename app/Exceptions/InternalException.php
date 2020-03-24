<?php
namespace App\Exceptions;
use Exception;
use Illuminate\Http\Request;
class InternalException extends Exception
{
    public function __construct($code = 5000, string $message = "")
    {
        parent::__construct($message, $code);
    }
    // 当这个异常被触发时，会调用 render 方法来输出给用户
    public function render(Request $request)
    {
//        if ($request->expectsJson()) {
//            return response()->json(['msg' => $this->msgForUser], $this->code);
            return api_error($this->code, [], $this->getMessage());
//        }
//        return view('pages.error', ['msg' => $this->msgForUser]);
    }
}