<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-29
 * Time: 上午1:36
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserPermission extends Model {

    protected $dateFormat = 'U';

    protected $guarded = [];

    public function permission()
    {
        return $this->hasOne(AdminRole::class,'id','permission_id');
    }
}