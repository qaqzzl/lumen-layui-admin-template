<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{


    //重写 throwValidationException 方法实现自定义消息
    protected function throwValidationException(Request $request, $validator)
    {
        $response = admin_error(4000, [], $validator->errors()->first());
        throw new ValidationException($validator, $response);
    }
}
