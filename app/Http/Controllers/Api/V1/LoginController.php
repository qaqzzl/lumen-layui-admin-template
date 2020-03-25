<?php
/**
 * Created by PhpStorm.
 * User: qaqzz
 * Date: 2020/2/27
 * Time: 19:50
 */

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\Service\CommonService;
use App\Http\Controllers\Api\Service\UserService;
use App\Models\UserAuths;
use App\Models\UserAuthsToken;
use App\Models\UserMember;
use EasyWeChat\Factory;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class LoginController extends BaseController
{

    // 手机号验证码登录
    public function phoneLogin(Request $request, CommonService $commonService, UserService $userService)
    {
        // 手机号 , 验证码类型
        $data = $this->validate($request,[
            'phone'=>'required',
            'verify_code'=>'required',
        ],[
            'phone.required' => '手机号不能为空',
            'verify_code.required' => '验证码不能为空',
        ]);
        if ( ! $commonService->PhoneVerifyCode($data['phone'], $data['verify_code'],'login') ) {
            return api_error(1003);
        }

        $res = $userService->Login($data['phone'], 'phone');

        return api_success($res);
    }

    // 账户密码登录
    public function accountLogin(Request $request, UserService $userService)
    {
        // 账号 , 密码
        $data = $this->validate($request,[
            'account'=>'required',
            'password'=>'required',
        ],[
            'account.required' => '账号不能为空',
            'password.required' => '密码不能为空',
        ]);
        if ( !$UserAuths = UserAuths::where(['identifier' => $data['account']])->first()) {
            return api_error(1002);
        }
        $member_id = $UserAuths->member_id;
        // 验证密码是否正确
        if ( !$UserMember = UserMember::where(['password'=>md5($data['password']),'member_id'=>$member_id])
            ->select('member_id','nickname','gender','birthdate','avatar','signature','city','province','created_at')
            ->first() ) {
            return api_error(1002);
        }
        //生成用户token
        $user_client_type = 'app';
        $UserAuthsToken = $userService->RefreshToken($member_id, $user_client_type);

        $auth = ['access_token' => $UserAuthsToken->token, 'user_id' => $member_id, 'client' => $user_client_type, 'expires_time' => $UserAuthsToken->last_time + 2592000];

        return api_success([
            '$auth'=>$auth,
            'member_info'=>$UserMember,
        ]);
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
    public function wechatOauthCallback(Request $request, UserService $userService)
    {
        $app = Factory::officialAccount(config('wechat.official_account.default'));
        $oauth = $app->oauth;

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();

        $res = $userService->Login($user->getId(), 'wechat_official', [
            'nickname'=>$user->getNickname(),
            'avatar'=>$user->getAvatar(),
        ]);

        return api_success($res);

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