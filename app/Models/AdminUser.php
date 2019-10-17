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
        'account', 'nickname', 'avatar', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'access_token'
    ];

    public function getAvatarAttributes($value)
    {
        if (empty($value))
            return '';
    }

    public function setPasswordAttribute($value)
    {
        if ($value)
            $this->attributes['password'] = md5($value);
    }

//    public function getCreatedAtAttribute($value)
//    {
//        return date('Y-m-d H:i:s', $value);
//    }




    public function user_role()
    {
        return $this->hasMany(AdminUserRole::class,'user_id','id');
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
            if (in_array("*",$vo->http_path)) {
                $root = true;
                break;
            }
        }
        if ($root == true) {
            $menu = AdminMenu::all();
        } else {
            $menu = AdminMenu::where()->get();
        }
        $menu = getChildren($menu);
        return $menu;
    }
}

function getChildren ($data, $pid=0) {
    $arr = array();
    foreach ($data as $vo) {
        if ($vo->parent_id == $pid) {
            $vo->children = getChildren($data,$vo->id);
            $vo->url = config('admin.domain_path').$vo->uri;
            $vo->spread = true;   //节点展开
            $arr[] = $vo;
        }
    }
    return $arr;
}
