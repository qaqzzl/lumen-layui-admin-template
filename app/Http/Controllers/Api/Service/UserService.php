<?php
/**
 * Created by PhpStorm.
 * User: qaqzz
 * Date: 2020/3/10
 * Time: 22:13
 */
namespace App\Http\Controllers\Api\Service;


use App\Exceptions\InternalException;
use App\Models\AdminUser;
use App\Models\UserAuths;
use App\Models\UserAuthsToken;
use App\Models\UserInvite;
use App\Models\UserMember;
use App\Models\UserWallet;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\DB;

class UserService {

    /**
     * 刷新|获取用户token
     * @param string $member_id
     * @param string $client
     * @return UserAuthsToken
     * @throws InternalException
     */
    public function RefreshToken(string $member_id, string $client)
    {
        $token = Uuid::uuid();

        $UserAuthsToken = UserAuthsToken::create([
            'member_id' => $member_id,
            'token' => $token,
            'client' => $client,
            'last_time' => time(),
            'created_at' => time()
        ]);
        if (!$UserAuthsToken) throw new InternalException();

        return $UserAuthsToken;
    }

    /**
     * 创建用户
     * @param array $member 用户信息 UserMember::create($member)
     * @param array $auths 账号信息 UserAuths::create($auths)
     * @return UserMember
     * @throws InternalException
     */
    public function CreateUser(array $member, array $auths)
    {
        DB::beginTransaction();
        // 创建会员基础表
        if (empty($member['nickname'])) $member['nickname'] = '会员 - '.mt_rand(100000,999999);
        if (! $UserMember = UserMember::create($member) ) {
            DB::rollBack();
            throw new InternalException();
        }
        // 创建会员账号
        $auths['member_id'] = $UserMember->member_id;
        if (! UserAuths::create($auths) ) {
            DB::rollBack();
            throw new InternalException();
        }
        DB::commit();
        return $UserMember;
    }

    // login
    public function Login($identifier, $identity_type, $user_data = [], $invite_code='')
    {
        if (!$UserAuths = UserAuths::where(['identifier' => $identifier, 'identity_type' => $identity_type])->first()) {
            //注册
            $UserMember = $this->CreateUser($user_data, [
                'identifier' => $identifier,
                'identity_type' => $identity_type,
            ]);
            $member_id = $UserMember->member_id;
        } else {
            $member_id = $UserAuths->member_id;
        }

        //生成用户token
        $user_client_type = 'app';
        $UserAuthsToken = $this->RefreshToken($member_id, $user_client_type);

        $auth = ['access_token' => $UserAuthsToken->token, 'user_id' => $member_id, 'client' => $user_client_type, 'expires_time' => $UserAuthsToken->last_time + 2592000];
        // 查询用户绑定信息
        if ($this->IsBindingInviteCode($member_id) == 'no' && $invite_code != '') {
            // 执行绑定邀请码逻辑
            //$this->BindInviteCode($member_id, $invite_code);
        }

        // 查询会员基本信息
        $UserMember = $this->MemberInfo($member_id);

        return [
            'auth'=>$auth,
            'member_info'=>$UserMember
        ];
    }

    /**
     * 获取会员基本信息
     * @param string $member_id
     * @return UserMember
     */
    public function MemberInfo(string $member_id)
    {
        return UserMember::where("member_id",$member_id)
            ->select('member_id','nickname','gender','birthdate','avatar','signature','city','province','created_at')
            ->first();
    }

    /**
     * 查询是否绑定邀请码
     * @param string $user_id
     * @return string yes|no
     */
    public function IsBindingInviteCode($user_id)
    {
//        if ( UserInvite::where('id',$user_id)->count() ) {
//            return 'yes';
//        }
        return 'no';
    }


    /**
     * 查询是否必须绑定邀请码
     * @param string $user_id
     * @return string yes|no
     */
    public function IsRequiredBindingInviteCode($user_id)
    {
        return 'no';
    }

    /**
     * 查询是否绑定手机号
     * @param string $user_id
     * @return string yes|no
     */
    public function IsBindingPhone($user_id)
    {
        return 'yes';
    }
}