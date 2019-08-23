<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-8-1
 * Time: 上午1:44
 */

namespace App\Http\Controllers\Admin\V1;


use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\AdminUserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AuthController extends BaseController {


    /**
     * 管理员用户 - 列表
     * @param int $page
     * @param int $limit
     * @return json $info
    */
    public function adminUserList(Request $request)
    {
        $limit = $request->input('limit',10);
        //with(['user_role:id', 'user_role.role:id,name','slug'])
        $adminUser = AdminUser::with(['user_role'=>function($query) {
            $query->select('user_id','role_id');
            $query->with(['role' => function ($query) {
                $query->select('id','name','slug');
            }]);
        }]);

        //搜索
        if (!empty($request->account))  //账号
            $adminUser->where('account','like',$request->account.'%');
        if (!empty($request->nickname)) //昵称
            $adminUser->where('nickname','like',$request->nickname.'%');
        if (!empty($request->role)) {   //角色
            $adminUser->whereHas('user_role', function ($query) use($request) {
                $query->where('role_id', $request->role);
            });
        }
        if (!empty($request->permission)) { //权限
            $adminUser->orWhereHas('user_permission', function ($query) use($request) {
                $query->where('permission_id', $request->permission);
            });
            $adminUser->orWhereHas('user_role.role_permission', function ($query) use($request) {
                $query->where('permission_id', $request->permission);
            });
        }

        $info = $adminUser->paginate($limit);
        return admin_success($info);
    }

    /**
     * 管理员用户 - 修改
    */
    public function adminUserSave(Request $request)
    {
        $data = $this->validate($request,[
            'id'=>'required',
            'nickname' => 'required',
        ],[
            'nickname.required' => '请填上你的大名'
        ]);

        if (AdminUser::where('id',$request->id)->update($data)) {
            return admin_success();
        }
        return admin_error(5000);
    }

    /**
     * 管理员用户 - 创建
     */
    public function adminUserCreate(Request $request)
    {
        $data = $this->validate($request,[
            'account'=>'required',
            'password'=>'required',
            'nickname'=>'required',
            'avatar'=>'',
        ],[
            'nickname.required' => '请填上你的大名'
        ]);
        if (AdminUser::create($data)) {
            return admin_success();
        }
        return admin_error(5000);
    }

    /**
     * 管理员用户 - 删除
    */
    public function adminUserDelete(Request $request)
    {
        $ids = array_flip($request->ids);
        unset($ids[1]);
        $ids = array_flip($ids);
//        $ids = array_values($ids);
        if (AdminUser::whereIn('id',$ids)->delete()) {
            return admin_success();
        }
        return admin_error(5000);
    }

    /**
     * 管理员用户 - 修改角色
    */
    public function adminUserRoleSave(Request $request)
    {
        if (AdminUserRole::where('user_id',$request->id)->delete() === false) return admin_error(5000);
        if (empty($request->input('role_list'))) return admin_success();
        foreach ($request->role_list as $vo) {
            $userRole[] = [
                'role_id'=>$vo['role_id'],
                'user_id'=>$request->id
            ];
        }
        if (AdminUserRole::insert($userRole)) {
            return admin_success();
        }
        return admin_error(5000);
    }

    /**
     * 管理员角色 - 列表
    */
    public function adminRoleList(Request $request)
    {
        $limit = $request->input('limit',10);
        //with(['user_role:id', 'user_role.role:id,name','slug'])
        $adminRole = AdminRole::with(['role_permission'=>function($query){
            $query->select('role_id','permission_id');
            $query->with(['permission' => function ($query) {
                return $query->select('id','name','slug');
            }]);
        }]);
        //搜索
        if (!empty($request->name))
            $adminRole->where('slug','like','%'.$request->slug.'%');
        if (!empty($request->slug))
            $adminRole->where('slug','like','%'.$request->slug.'%');

        $info = $adminRole->paginate($limit);
        return admin_success($info);
    }

    /**
     * 管理员权限列表
    */
    public function adminPermissionList(Request $request)
    {
        $limit = $request->input('limit',10);
        //with(['user_role:id', 'user_role.role:id,name','slug'])
        $adminPermission = AdminPermission::select('*');
        //搜索
        if (!empty($request->name))
            $adminPermission->where('name','like','%'.$request->name.'%');
        if (!empty($request->slug))
            $adminPermission->where('slug','like','%'.$request->slug.'%');

        $info = $adminPermission->paginate($limit);

        foreach ($info as &$vo) {
            $vo->http_method = explode(',',$vo->http_method);
            $vo->http_path = explode("\n",$vo->http_path);
        }

        return admin_success($info);
    }
}