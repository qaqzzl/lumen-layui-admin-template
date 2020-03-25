<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group([
    'namespace' => 'Admin\V1',
    'prefix' => 'admin/v1'
], function() use ($router)
{
    $router->post('test', 'AppController@test');                            //测试

    #登录注册
    $router->post('login/signin', 'LoginController@signin');                //登陆




    $router->group(['middleware' => 'auth.admin.login'], function() use ($router) {
        $router->post('auth/menu', 'IndexController@getMenu');                                  //获取用户菜单

        //权限验证中间件
        $router->group(['middleware'=>'verify.admin.permission'], function() use ($router) {
            #auth
            $router->post('auth/admin.user.list', 'AuthController@adminUserList');                           //管理员用户列表
            $router->post('auth/admin.user.create', 'AuthController@adminUserCreate');                       //管理员用户添加
            $router->post('auth/admin.user.save', 'AuthController@adminUserSave');                           //管理员用户修改
            $router->post('auth/admin.user.delete', 'AuthController@adminUserDelete');                       //管理员用户删除
            $router->post('auth/admin.user.role.save', 'AuthController@adminUserRoleSave');                  //管理员用户角色修改

            $router->post('auth/admin.role.list', 'AuthController@adminRoleList');                           //管理员角色列表
            $router->post('auth/admin.role.create', 'AuthController@adminRoleCreate');                       //管理员角色添加
            $router->post('auth/admin.role.save', 'AuthController@adminRoleSave');                           //管理员角色修改
            $router->post('auth/admin.role.delete', 'AuthController@adminRoleDelete');                       //管理员角色删除
            $router->post('auth/admin.role.permission.save', 'AuthController@adminRolePermissionSave');      //管理员角色权限修改

            $router->post('auth/admin.permission.list', 'AuthController@adminPermissionList');               //管理员权限列表
            $router->post('auth/admin.permission.create', 'AuthController@adminPermissionCreate');           //管理员权限添加
            $router->post('auth/admin.permission.save', 'AuthController@adminPermissionSave');               //管理员权限修改
            $router->post('auth/admin.permission.delete', 'AuthController@adminPermissionDelete');           //管理员权限删除

            $router->post('auth/admin.menu.list', 'AuthController@adminMenuList');                           //管理员权限列表
            $router->post('auth/admin.menu.create', 'AuthController@adminMenuCreate');                       //管理员权限添加
            $router->post('auth/admin.menu.save', 'AuthController@adminMenuSave');                           //管理员权限添加
            $router->post('auth/admin.menu.delete', 'AuthController@adminMenuDelete');                       //管理员权限删除

            $router->post('system/config.list', 'SystemController@configList');                              //系统配置列表
            $router->post('system/config.create', 'SystemController@configCreate');                          //系统配置添加


        });
    });
});



$router->group([
    'namespace' => 'Api\V1',
    'prefix' => 'api/v1'
], function() use ($router)
{
    $router->post('test', 'BaseController@test');                            // 测试

    # 公共接口
    $router->post('common/send_sms', 'LoginController@sendSms');               // 发送短信验证码

    # 登录注册
    $router->post('login/phone', 'LoginController@phoneLogin');               // 手机验证码登录/注册
    $router->post('login/account', 'LoginController@accountLogin');               // 账号密码登录
    $router->get('login/wechat', 'LoginController@wechatLogin');                                      // 微信登录URL
    $router->get('login/wechat_oauth_callback', 'LoginController@wechatOauthCallback');               // 微信登录回调
    $router->post('login/wechat_oauth_callback', 'LoginController@wechatOauthCallback');              // 微信登录回调


    $router->group(['middleware' => 'auth.api.login'], function() use ($router) {

        #用户
        $router->post('user/get_member_info', 'UserController@GetMemberInfo');  // 获取用户基本信息
    });

});
