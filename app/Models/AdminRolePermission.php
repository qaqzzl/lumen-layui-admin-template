<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-29
 * Time: 上午1:41
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRolePermission extends Model {


    protected $dateFormat = 'U';

    protected $guarded = [];

    //一对多关联方向 , 作用跟一对一关联类似
//    public function role()
//    {
//        return $this->belongsTo(AdminRole::class,'id','role_id');
//    }

    public function permission()
    {
        return $this->hasOne(AdminPermission::class,'id','permission_id');
    }

}