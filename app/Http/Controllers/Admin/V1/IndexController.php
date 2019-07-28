<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-28
 * Time: 下午8:50
 */

namespace App\Http\Controllers\Admin\V1;


use App\Http\Controllers\Admin\Service\AuthService;
use Illuminate\Http\Request;

class IndexController extends BaseController {

    /**
     * 获取用户菜单
    */
    public function getMenu(Request $request, AuthService $authService)
    {

        $authService->GetMenu($request->user_id);
    }
}