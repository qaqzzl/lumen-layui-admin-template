<?php
/**
 * Created by PhpStorm.
 * User: qaqzz
 * Date: 2020/3/10
 * Time: 21:25
 */
namespace App\Http\Middleware;

use App\Models\AdminUser;
use App\Models\UserAuthsToken;
use Closure;

class AuthApiLoginMiddleware
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
        $user_id = $request->header('uid');
        $access_token = $request->header('access-token');

        if ( !$user_id || !$access_token) {
            $user_id = $request->input('uid');
            $access_token = $request->input('token');
        }

        $request->offsetSet('uid',$user_id);
        if (!$UserAuthsToken = UserAuthsToken::where(['member_id'=>$user_id,'token'=>$access_token])->first()) {
            return api_error(1000);
        }

        switch ($UserAuthsToken->status) {
            case 1:
                return api_error(1001);
        }
        return $next($request);
    }
}