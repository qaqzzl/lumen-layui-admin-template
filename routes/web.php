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
        $router->post('auth/menu', 'IndexController@getMenu');                              //获取用户菜单

        //权限验证
        $router->group(['middleware'=>'verify.admin.permission'], function() use ($router) {

        });
    });
});