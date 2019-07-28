<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-28
 * Time: 下午5:52
 */

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Admin\Service\UserService;
use App\Models\AdminUser;
use Illuminate\Http\Request;

class LoginController extends BaseController {


    /**
     * 登陆
     * @param string $account
     * @param static $password
    */
    public function signin(Request $request, UserService $userService)
    {
        if (!$user = AdminUser::where('account',$request->input('account'))->first()) {
            return admin_error(1002);
        }
        if ($user->password != md5($request->input('password')) ) return admin_error(1002);

        $token = $userService->RefreshToken($user);

        return admin_success(['access_token'=>$token,'user_id'=>$user->id]);
    }
}