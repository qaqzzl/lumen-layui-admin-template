<?php
/**
 * Created by PhpStorm.
 * Description: 用户控制器
 * User: qaqzz
 * Date: 2020/2/27
 * Time: 19:48
 */
namespace App\Http\Controllers\Api\V1;

use App\Models\LotteryOrder;
use App\Models\UserBill;
use App\Models\UserMember;
use App\Models\UserWallet;
use App\Models\UserWalletWithdrawApply;
use EasyWeChat\Factory;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController{

    // 获取用户信息
    public function GetMemberInfo(Request $request)
    {
        $member_id = $request->uid;
//        $UserMember = UserMember::with(['user_auths'=>function($query) {
//            $query->select('*');
//        }])->where('member_id',$member_id)->first();

        $UserMember = UserMember::where('member_id',$member_id)->first();

        return api_success($UserMember);
    }

}
