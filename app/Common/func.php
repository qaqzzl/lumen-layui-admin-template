<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-28
 * Time: 下午6:44
 */


function admin_success($data = [], $code=0, $msg = '')
{
    if (empty($data)) $data = (object)[];
    if ($msg == '') {
        $msg = config('admin_code')[$code];
    }
    $json = [
        'code'=>$code,
        'data'=>$data,
        'msg'=>$msg
    ];
    return response()->json($json);
}

function admin_error($code = 5000, $data = [], $msg = '')
{
    if (empty($data)) $data = (object)[];
    if ($msg == '') {
        $msg = config('admin_code')[$code];
    }
    $json = [
        'code'=>$code,
        'data'=>$data,
        'msg'=>$msg
    ];
    return response()->json($json);
}