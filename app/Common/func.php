<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-28
 * Time: 下午6:44
 */

// admin response json func
function admin_success($data = [], $msg = '', $code=0)
{
    if (empty($data)) $data = (object)[];
    if ($msg == '') {
        $msg = config('admin_response_code')[$code];
    }
    $json = [
        'code'=>$code,
        'data'=>$data,
        'msg'=>$msg
    ];
    return response()->json($json);
}
// admin response json func
function admin_error($code = 5000, $data = [], $msg = '')
{
    if (empty($data)) $data = (object)[];
    if ($msg == '') {
        $msg = config('admin_response_code')[$code];
    }
    $json = [
        'code'=>$code,
        'data'=>$data,
        'msg'=>$msg
    ];
    return response()->json($json);
}


// api response json func
function api_success($data = [], $msg = '', $code=0)
{
    if (empty($data)) $data = (object)[];
    if ($msg == '') {
        $msg = config('api_response_code')[$code];
    }
    $json = [
        'code'=>$code,
        'data'=>$data,
        'msg'=>$msg
    ];
    return response()->json($json);
}
// api response json func
function api_error($code=5000, $data = [], $msg = '')
{
    if (empty($data)) $data = (object)[];
    if ($msg == '') {
        $msg = config('api_response_code')[$code];
    }
    $json = [
        'code'=>$code,
        'data'=>$data,
        'msg'=>$msg
    ];
    return response()->json($json);
}