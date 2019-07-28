<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-28
 * Time: 下午6:00
 */

namespace App\Http\Controllers\Admin\Service;

use App\Models\AdminUser;
use Faker\Provider\Uuid;

class UserService {

    //刷新token
    public function RefreshToken(AdminUser $adminUser)
    {
        $token = Uuid::uuid();

        $adminUser->access_token = $token;

        $adminUser->save();

        return $token;
    }


}