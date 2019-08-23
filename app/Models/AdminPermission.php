<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-29
 * Time: 上午1:35
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model {


    protected $dateFormat = 'U';

    protected $guarded = [];

//    public function setHttpMethodAttribute($value)
//    {
//        if (is_array($value))
//            $this->attributes['http_method'] = implode(',',$value);
//    }

}