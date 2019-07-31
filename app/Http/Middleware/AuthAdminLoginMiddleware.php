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
        if (AdminUser::where(['id'=>$request->input('user_id')])->count()) {
            // 在这里可以定制你想要的返回格式, 亦或者是 JSON 编码格式
            return $next($request);
        }
        return admin_error(1001);
    }
}
