<?php
/**
 * Created by PhpStorm.
 * User: qaqzz
 * Date: 2020/2/27
 * Time: 19:50
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Service\CommonService;
use App\Models\UserAuths;
use App\Models\UserAuthsToken;
use App\Service\UserService;
use EasyWeChat\Factory;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class LoginController extends BaseController
{

    // 手机号验证码登录
    public function phoneLogin(Request $request, CommonService $commonService)
    {
        // 手机号 , 验证码类型
        $data = $this->validate($request,[
            'phone'=>'required',
            'verify_class'=>'required',
        ],[
            'phone.required' => '手机号不能为空',
            'verify_class.required' => '验证码类型不能为空',
        ]);
        $commonService->SendSms($data['phone'], $data['verify_class']);

        return api_success(null,'短信验证码发送成功');
    }

    // 账户密码登录
    public function accountLogin()
    {

    }

    // 微信登录
    public function wechatLogin(Request $request)
    {
        $config = config('wechat.official_account.default');
        $config['oauth']['callback'] = $config['oauth']['callback'] . '?invite_code='. $request->input('invite_code');
        $app = Factory::officialAccount($config);
        $oauth = $app->oauth;
        return $oauth->redirect();
    }

    // 微信登录授权回调
    public function wechatOauthCallback(Request $request,UserService $userService)
    {
        $app = Factory::officialAccount(config('wechat.official_account.default'));
        $oauth = $app->oauth;

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();

        if (!$UserAuths = UserAuths::where(['identifier' => $user->getId(), 'identity_type' => 'wechat_official'])->first()) {
            //注册
            $UserMember = $userService->CreateUser([
                'nickname'=>$user->getNickname(),
                'avatar'=>$user->getAvatar(),
            ], [
                'identifier' => $user->getId(),
                'identity_type' => 'wechat_official',
            ]);
            $member_id = $UserMember->member_id;
        } else {
            $member_id = $UserAuths->member_id;
        }

        //生成用户token
        $user_client_type = 'wechat_official';
        $UserAuthsToken = $userService->RefreshToken($member_id, $user_client_type);

        $auth = ['access_token' => $UserAuthsToken->token, 'user_id' => $member_id, 'client' => $user_client_type, 'expires_time' => $UserAuthsToken->last_time + 2592000];
        // 查询用户绑定信息
        if ($userService->IsBindingInviteCode($member_id) == 'no' && $request->input('invite_code')) {
            // 执行绑定邀请码逻辑
            $userService->BindInviteCode($member_id, $request->input('invite_code'));
        }

        // 查询会员基本信息
        $UserMember = $userService->MemberInfo($member_id);

        return api_success([
            'auth'=>$auth,
            'member_info'=>$UserMember
        ]);
    }


    // QQ登录
    public function qqLogin()
    {

    }

    // 微博登录
    public function weiBoLogin()
    {

    }
}