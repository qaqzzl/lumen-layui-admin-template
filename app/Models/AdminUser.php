<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    protected $table = 'admin_users';


    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'nickname', 'avatar'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    public function role()
    {

    }

    public function user_permission()
    {
        return $this->hasMany(AdminUserPermission::class,'user_id','id');
    }


    //获取用户权限
    public static function GetPermissions($user_id)
    {
        $permission = array();
        //查询用户权限表
        $userPermission = AdminUserPermission::with(['permission' => function ($query) {
            $query->select('*');
        }])->where('user_id',$user_id)->get();
        foreach ($userPermission as $vo) {
            $permission[] = $vo->permission;
        }

        //查询用户角色权限表
        $userPermission2 = AdminUserRole::with(['role.role_permission.permission' => function ($query) {
            $query->select('*');
        }])->where('user_id',$user_id)->get();
        foreach ($userPermission2 as $v) {
            foreach ($v->role->role_permission as $vo) {
                $permission[] = $vo->permission;
            }
        }

        return $permission;
    }

    //获取用户菜单
    public static function GetMenu($user_id)
    {
        $permission = self::GetPermissions($user_id);
        $root = false;  //是否具备超级权限 *
        foreach ($permission as $vo) {
            if ($vo->http_path == '*') {
                $root = true;
                break;
            }
        }
        if ($root == true) {
            $menu = AdminMenu::all();
        } else {
            $menu = AdminMenu::where()->get();
        }
        dump($menu);
    }
}
