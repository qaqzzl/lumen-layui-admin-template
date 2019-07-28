<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-29
 * Time: 上午12:43
 */

namespace App\Http\Controllers\Admin\Service;

use App\Models\AdminUser;
use App\Models\AdminUserPermission;
use App\Models\AdminUserRole;
class AuthService {

    //获取用户权限
    public function GetPermissions($user_id)
    {
        //查询用户权限表
        $userPermission = AdminUserPermission::with(['permission' => function ($query) {
            $query->select('*');
        }])->where('user_id',$user_id)->get();
        dd($userPermission);

        //查询用户角色权限表
    }


    //验证用户权限
    public function VerifyPermissions()
    {

    }

    //获取用户菜单
    public function GetMenu($user_id)
    {
        $this->GetPermissions($user_id);
    }
}