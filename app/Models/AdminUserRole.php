<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-29
 * Time: 上午1:41
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AdminUserRole extends Model {

    protected $dateFormat = 'U';


    protected $guarded = [];



    /**
     * Get the role associated with the user. 一对一关联
     */
    public function role()
    {
        return $this->hasOne(AdminRole::class,'id','role_id');
    }

    public function role_permission()
    {
        return $this->hasMany(AdminRolePermission::class, 'role_id','role_id');
    }
}
