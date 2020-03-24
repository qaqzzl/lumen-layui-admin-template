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

    // 会员账单
    public function getBill(Request $request)
    {
        $per_page = $request->input('per_page',10);
        $UserBill = UserBill::where(['member_id'=>$request->uid])->paginate($per_page);
        $UserBill = $UserBill->toArray();
        unset($UserBill['first_page_url'],$UserBill['from'],$UserBill['next_page_url'],$UserBill['last_page_url'],$UserBill['path'],$UserBill['prev_page_url'],$UserBill['to']);
        return api_success($UserBill);
    }

    // 会员钱包
    public function wallet(Request $request)
    {
        $UserWallet = UserWallet::where(['member_id'=>$request->uid])->first();
        $today_time = strtotime(date('Y-m-d',time()));
        $yesterday_time = $today_time - 3600 * 24;
        // 今日收益
        $today = 0.00;
        // 昨日收益
        $yesterday = 0.00;
        // 抽奖收益
        $LotteryOrder = LotteryOrder::where(['pay_status'=>1,'member_id'=>$request->uid])->where('pay_time','>',$yesterday_time)->get();
        foreach ($LotteryOrder as $vo) {
            if ($vo->pay_time > $today_time) {
                $today += $vo->win_amount;
            } else {
                $yesterday += $vo->win_amount;
            }
        }
        // 邀请收益
        $UserBill = UserBill::where(['member_id'=>$request->uid])->where('amount','>',0)->where('created_at','>',$yesterday_time)->get();
        foreach ($UserBill as $vo) {
            if ($vo->created_at > $today_time) {
                $today += $vo->amount;
            } else {
                $yesterday += $vo->amount;
            }
        }
        $UserWallet->today = floor($today * 100) / 100;
        $UserWallet->yesterday = floor($yesterday * 100) / 100;
        return api_success($UserWallet);
    }

    // 绑定支付宝账号
    public function bindAlipayAccount(Request $request)
    {
        $data = $this->validate($request,[
            'alipay_account'=>'required',
            'alipay_account_name'=>'',
        ],[
            'alipay_account_name.required' => '提现账户昵称不能为空',
            'alipay_account.required' => '提现账户不能为空',
        ]);
        if (UserMember::where('member_id',$request->uid)->update($data)) {
            return api_success();
        }
        return api_error();
    }

    // 会员余额提现
    public function walletWithdraw(Request $request)
    {
        $data = $this->validate($request,[
            'withdraw_money'=>'required',
        ],[
            'withdraw_money.required' => '提现金额不能空',
        ]);
        $UserMember = UserMember::where('member_id',$request->uid)->first();
        if (!$UserMember || !$UserMember->alipay_account || !$UserMember->alipay_account_name) {
            return api_error(2100);
        }

        $withdraw_money = $request->withdraw_money;

        $data['withdraw_money'] = $withdraw_money;
        $data['platform'] = 'alipay';
        $data['account_name'] = $UserMember->alipay_account_name;
        $data['account'] = $UserMember->alipay_account;


        $UserWallet = UserWallet::where('member_id',$request->uid)->select('balance_money','pending_withdraw_money')->first();
        if ($UserWallet->balance_money < $withdraw_money) {
            return api_error(2101);
        }
        DB::beginTransaction();
        if (! UserWallet::where('member_id',$request->uid)->update([
            'balance_money'=>$UserWallet->balance_money - $withdraw_money,
            'pending_withdraw_money'=>$UserWallet->pending_withdraw_money + $withdraw_money,
        ]) ) {
            DB::rollBack();
            return api_error(5000);
        }

        $data['member_id'] = $request->uid;
        if (! UserWalletWithdrawApply::create($data) ) {
            DB::rollBack();
            return api_error(5000);
        }
        DB::commit();
        return api_success();
    }

    // 我的二维码
    public function qrcode(Request $request)
    {
        $invite_code = $request->input('invite_code',888888);
        header('location: ','http://qr.topscan.com/api.php?text='.urlencode('http://www.baidu.com?invite_code='.$invite_code));
        exit;
    }

    // 我的邀请人
    public function invite(Request $request)
    {
        $invite = $request->invite;
        $member_id = $request->uid;
        $sql[] = "select ui.created_at,um.nickname, um.avatar 
FROM user_invite as ui
right JOIN user_member as um ON um.member_id = ui.id
where ";
        switch ($invite) {
            case 'all':         // 查询全部 3级
                $sql[] = "ui.path REGEXP '^{$member_id}$' OR ui.path REGEXP '^{$member_id},[0-9]*$' OR ui.path REGEXP '^{$member_id},[0-9]*,[0-9]$'";
            break;
            case '1':           // 查询1级
                $sql[] = "ui.path REGEXP '^{$member_id}$'";
            break;
            case '2':           // 查询2级
                $sql[] = "ui.path REGEXP '^{$member_id},[0-9]*$'";
            break;
            case '3':           // 查询3级
                $sql[] = "ui.path REGEXP '^{$member_id},[0-9]*,[0-9]$'";
            break;
        }
        $invite_count_1 = DB::select("select count(*) as `count` from user_invite where 
         path REGEXP '^{$member_id}$'")[0]->count;
        $invite_count_2 = DB::select("select count(*) as `count` from user_invite where 
         path REGEXP '^{$member_id},[0-9]*$'")[0]->count;
        $invite_count_3 = DB::select("select count(*) as `count` from user_invite where 
         path REGEXP '^{$member_id},[0-9]*,[0-9]*$'")[0]->count;

        $page = $request->input('page',1);
        $pagesize1 = ($page-1)*10;
        $pagesize2 = $page*10;
        $sql[] = "order by ui.created_at desc limit {$pagesize1},{$pagesize2}";
        $sql = implode(' ', $sql);

        $res = DB::select($sql);

        return api_success([
            'invite'=>$res,
            'invite_count_all'=>$invite_count_1 + $invite_count_2 + $invite_count_3,
            'invite_count_1'=>$invite_count_1,
            'invite_count_2'=>$invite_count_2,
            'invite_count_3'=>$invite_count_3,
        ]);
    }

}
