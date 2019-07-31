/**
 
 @Name：layuiAdmin 公共业务
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License：LPPL
 
 */

layui.define(function(exports){
    var $ = layui.$
        ,layer = layui.layer
        ,laytpl = layui.laytpl
        ,setter = layui.setter
        ,view = layui.view
        ,admin = layui.admin
    
    //公共业务的逻辑处理可以写在此处，切换任何页面都会执行
    //……
    var reqajax = {};
    reqajax[setter.request.tokenName] = layui.data(setter.tableName)[setter.request.tokenName] || ''
    reqajax[setter.request.idName] = layui.data(setter.tableName)[setter.request.idName] || ''
    $.ajaxSetup( {
        // url: "/index.html" , // 默认URL
        // aysnc: false ,       // 默认同步加载
        type: "POST" ,          // 默认使用POST方式
        data:reqajax,           //默认添加额外参数
        // headers: {           // 默认添加请求头
        //   "Author": "CodePlayer" ,
        //   "Powered-By": "CodePlayer"
        // } ,
    });
    
    
    //退出
    admin.events.logout = function(){
        //执行退出接口
        admin.req({
            url: layui.setter.base + 'json/user/logout.js'
            ,type: 'get'
            ,data: {}
            ,done: function(res){ //这里要说明一下：done 是只有 response 的 code 正常才会执行。而 succese 则是只要 http 为 200 就会执行
                
                //清空本地记录的 token，并跳转到登入页
                admin.exit(function(){
                    location.href = 'user/login.html';
                });
            }
        });
    };
    
    
    //对外暴露的接口
    exports('common', {});
});