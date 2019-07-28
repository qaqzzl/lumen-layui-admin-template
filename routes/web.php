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



    $router->post('auth/menu', 'IndexController@getMenu');                  //获取用户菜单
//    $router->group(['middleware' => 'auth.api.login'], function() use ($router) {
//        #我的
//        $router->post('user/get_member_info', 'UserController@getMemberInfo');      //获取会员基本信息
//        $router->post('user/update_member_info', 'UserController@updateMemberInfo');      //更新会员基本信息
//    });
});