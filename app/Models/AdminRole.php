<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-29
 * Time: 上午1:41
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model {


    protected $dateFormat = 'U';

    protected $guarded = [];


    //一对多关联
    public function role_permission()
    {
        return $this->hasMany(AdminRolePermission::class, 'role_id','id');
    }
}