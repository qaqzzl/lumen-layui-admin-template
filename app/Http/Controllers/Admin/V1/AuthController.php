<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-8-1
 * Time: 上午1:44
 */

namespace App\Http\Controllers\Admin\V1;


use App\Models\AdminMenu;
use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminRolePermission;
use App\Models\AdminUser;
use App\Models\AdminUserRole;
use function App\Models\getChildren;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $info = $adminUser->paginate($limit)->toArray();
        $info['data'] = array_map(function ($vo) {
            $vo['created_at'] = date('Y-m-d',$vo['created_at']);
            return $vo;
        },$info['data']);
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
     * 管理员用户 - 角色修改
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
        $adminRole = AdminRole::with(['role_permission'=>function($query){
            $query->select('role_id','permission_id');
            $query->with(['permission' => function ($query) {
                return $query->select('id','name','slug');
            }]);
        }]);
        //搜索
        if (!empty($request->name))
            $adminRole->where('name','like','%'.$request->name.'%');
        if (!empty($request->slug))
            $adminRole->where('slug','like','%'.$request->slug.'%');
        if (!empty($request->permission)) { //权限
            $adminRole->WhereHas('role_permission.permission', function ($query) use($request) {
                $query->where('permission_id', $request->permission);
            });
        }

        $info = $adminRole->paginate($limit)->toArray();
        $info['data'] = array_map(function ($vo) {
            $vo['created_at'] = date('Y-m-d',$vo['created_at']);
            $vo['updated_at'] = date('Y-m-d',$vo['updated_at']);
            return $vo;
        },$info['data']);
        return admin_success($info);
    }

    /**
     * 管理员角色 - 添加
    */
    public function adminRoleCreate(Request $request)
    {
        $data = $this->validate($request,[
            'name'=>'required',
            'slug'=>'required',
        ],[
            'name.required' => '请填上角色名称'
        ]);
        if (AdminRole::create($data)) {
            return admin_success();
        }
        return admin_error(5000);
    }

    /**
     * 管理员角色 - 修改
    */
    public function adminRoleSave(Request $request)
    {
        $data = $this->validate($request,[
            'name'=>'required',
            'slug'=>'required',
        ],[
            'name.required' => '请填上角色名称'
        ]);

        if (AdminRole::where('id',$request->id)->update($data)) {
            return admin_success();
        }
        return admin_error(5000);
    }

    /**
     * 管理员角色 - 删除
    */
    public function adminRoleDelete(Request $request)
    {
        $ids = array_flip($request->ids);
        unset($ids[1]);
        $ids = array_flip($ids);
        if (AdminRole::whereIn('id',$ids)->delete()) {
            return admin_success();
        }
        return admin_error(5000);
    }

    /**
     * 管理员角色 - 权限修改
    */
    public function adminRolePermissionSave(Request $request)
    {
        if (AdminRolePermission::where('role_id',$request->id)->delete() === false) return admin_error(5000);
        if (empty($request->input('permission_list'))) return admin_success();
        foreach ($request->permission_list as $vo) {
            $userRole[] = [
                'permission_id'=>$vo['permission_id'],
                'role_id'=>$request->id
            ];
        }
        if (AdminRolePermission::insert($userRole)) {
            return admin_success();
        }
        return admin_error(5000);
    }


    /**
     * 管理员权限 - 列表
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

        $info = $adminPermission->paginate($limit)->toArray();
        foreach ($info['data'] as &$vo) {
            $vo['created_at'] = date('Y-m-d',$vo['created_at']);
            $vo['updated_at'] = date('Y-m-d',$vo['updated_at']);
        }
        return admin_success($info);
    }

    /**
     * 管理员权限 - 添加
    */
    public function adminPermissionCreate(Request $request)
    {
        $data = $this->validate($request,[
            'name'=>'required',
            'slug'=>'required',
            'http_method'=>'',
            'http_path'=>'',
        ]);
        $data['http_method'] = implode(',',$data['http_method']);
        if (AdminPermission::create($data)) {
            return admin_success();
        }
        return admin_error(5000);
    }

    /**
     * 管理员权限 - 修改
    */
    public function adminPermissionSave(Request $request)
    {
        $data = $this->validate($request,[
            'name'=>'required',
            'slug'=>'required',
            'http_method'=>'',
            'http_path'=>'',
        ]);
        $data['http_method'] = implode(',',$data['http_method']);
        if (AdminPermission::where('id',$request->id)->update($data)) {
            return admin_success();
        }
        return admin_error(5000);
    }

    /**
     * 管理员权限 - 删除
    */
    public function adminPermissionDelete(Request $request)
    {
        $ids = array_flip($request->ids);
        unset($ids[1]);
        $ids = array_flip($ids);
        if (AdminPermission::whereIn('id',$ids)->delete()) {
            return admin_success();
        }
        return admin_error(5000);
    }



    /**
     * 管理员菜单 - 列表
    */
    public function adminMenuList(Request $request)
    {
        $menu = AdminMenu::all();
        $menu = getChildren($menu);
        return admin_success(['menu'=>$menu]);
    }

    /**
     * 管理员菜单 - 添加
    */
    public function adminMenuCreate(Request $request)
    {
        $data = $this->validate($request,[
            'title'=>'required',
            'parent_id'=>'required',
            'uri'=>'',
            'icon'=>'',
            'order'=>'integer',
            'permission'=>'',
        ]);
//        $data['permission'] = implode(',',$data['permission']);
        if (AdminMenu::create($data)) {
            return admin_success();
        }
        return admin_error(5000);
    }

    /**
     * 管理员菜单 - 修改
    */
    public function adminMenuSave()
    {

    }

    /**
     * 管理员菜单 - 删除
    */
    public function adminMenuDelete(Request $request)
    {
        $ids = array_flip($request->ids);
        unset($ids[1],$ids[2],$ids[3],$ids[4],$ids[5],$ids[6],$ids[7]);
        $ids = array_flip($ids);
        foreach ($ids as $vo) { //判断是否存在下级
            if ($menu = AdminMenu::where('parent_id',$vo)->first()) return admin_error(6100,'','请删除'.$menu['title'].'下级菜单');
        }
        if (AdminMenu::whereIn('id',$ids)->delete()) {
            return admin_success();
        }
        return admin_error(5000);
    }
}