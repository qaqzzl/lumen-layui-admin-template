<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Admin\Service\AuthService;
use App\Models\AdminUser;
use Closure;

class VerifyAdminPermissionMiddleware
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
        $authService = new AuthService;
        if ($authService->VerifyPermissions()) {
            // 在这里可以定制你想要的返回格式, 亦或者是 JSON 编码格式
            return $next($request);
        }
        return admin_error(2000);
    }
}
