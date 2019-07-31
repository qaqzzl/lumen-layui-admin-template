<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-8-1
 * Time: 上午1:44
 */

namespace App\Http\Controllers\Admin\V1;


use App\Models\AdminUser;
use Illuminate\Http\Request;

class AuthController extends BaseController {


    /**
     * 获取管理员列表
     * @param int $page
     * @param int $limit
     * @return json $info
    */
    public function adminUserList(Request $request)
    {
        $limit = $request->input('limit',10);
        $info = AdminUser::paginate($limit);
        return admin_success($info);
    }
}