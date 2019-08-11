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

        //权限验证
        $router->group(['middleware'=>'verify.admin.permission'], function() use ($router) {
            #auth
            $router->post('auth/admin.user.list', 'AuthController@adminUserList');               //管理员列表
            $router->post('auth/admin.user.create', 'AuthController@adminUserCreate');           //管理员添加
            $router->post('auth/admin.user.save', 'AuthController@adminUserSave');               //管理员修改
            $router->post('auth/admin.user.delete', 'AuthController@adminUserDelete');           //管理员删除

            $router->post('auth/admin.role.list', 'AuthController@adminRoleList');               //角色列表
            $router->post('auth/admin.role.create', 'AuthController@adminRoleCreate');           //角色添加
            $router->post('auth/admin.role.save', 'AuthController@adminRoleSave');               //角色修改
            $router->post('auth/admin.role.delete', 'AuthController@adminRoleDelete');           //角色删除
        });
    });
});