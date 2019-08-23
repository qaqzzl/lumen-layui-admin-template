<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use Closure;

class AuthAdminLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //记录坑 , header 头名称 使用下划线会获取不到值 , 解决方案 ,设置nginx参数 underscores_in_headers on;
        $user_id = $request->header('user-id');
        $access_token = $request->header('access-token');
        $request->offsetSet('user_id',$user_id);
        if ($token = AdminUser::where(['id'=>$user_id])->value('access_token')) {
            if ($token == $access_token) {
                // 在这里可以定制你想要的返回格式, 亦或者是 JSON 编码格式
                return $next($request);
            }
        }
        return admin_error(1001);
    }
}
