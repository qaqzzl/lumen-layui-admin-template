/**
 
 @Name：layuiAdmin iframe版主入口
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License：LPPL
 
 */

layui.extend({
    setter: 'config' //配置模块
    ,admin: 'lib/admin' //核心模块
    ,view: 'lib/view' //视图渲染模块
}).define(['setter', 'admin'], function(exports){
    var setter = layui.setter
        ,element = layui.element
        ,admin = layui.admin
        ,tabsPage = admin.tabsPage
        ,view = layui.view
        
        //打开标签页
        ,openTabsPage = function(url, text){
            //遍历页签选项卡
            var matchTo
                ,tabs = $('#LAY_app_tabsheader>li')
                ,path = url.replace(/(^http(s*):)|(\?[\s\S]*$)/g, '');
            
            tabs.each(function(index){
                var li = $(this)
                    ,layid = li.attr('lay-id');
                
                if(layid === url){
                    matchTo = true;
                    tabsPage.index = index;
                }
            });
            
            text = text || '新标签页';
            
            if(setter.pageTabs){
                //如果未在选项卡中匹配到，则追加选项卡
                if(!matchTo){
                    $(APP_BODY).append([
                        '<div class="layadmin-tabsbody-item layui-show">'
                        ,'<iframe src="'+ url +'" frameborder="0" class="layadmin-iframe"></iframe>'
                        ,'</div>'
                    ].join(''));
                    tabsPage.index = tabs.length;
                    element.tabAdd(FILTER_TAB_TBAS, {
                        title: '<span>'+ text +'</span>'
                        ,id: url
                        ,attr: path
                    });
                }
            } else {
                var iframe = admin.tabsBody(admin.tabsPage.index).find('.layadmin-iframe');
                iframe[0].contentWindow.location.href = url;
            }
            
            //定位当前tabs
            element.tabChange(FILTER_TAB_TBAS, url);
            admin.tabsBodyChange(tabsPage.index, {
                url: url
                ,text: text
            });
        }
        
        ,APP_BODY = '#LAY_app_body', FILTER_TAB_TBAS = 'layadmin-layout-tabs'
        ,$ = layui.$, $win = $(window)
    
        //获取用户菜单 , 需要这样写 ， 外部才能调用
        ,_menu = function () {
            admin.req({
                url: layui.setter.api_domain + 'auth/menu' //实际使用请改成服务端真实接口
                ,done: function(res){
                    menu = res.data.menu
                    menuHtml = createMenuHtml(menu)
                    $('#LAY-system-side-menu').html(menuHtml)
                    var element = layui.element; //导航的hover效果、二级菜单等功能，需要依赖element模块
                    element.init();
                }
            });
        }
        ,getQueryVariable = function (variable)
        {
            var query = window.location.search.substring(1);
            var vars = query.split("&");
            for (var i=0;i<vars.length;i++) {
                var pair = vars[i].split("=");
                if(pair[0] == variable){return pair[1];}
            }
            return(false);
        }
    
    //初始
    if(admin.screen() < 2) admin.sideFlexible();
    
    //将模块根路径设置为 controller 目录
    layui.config({
        base: setter.base + 'modules/'
    });
    
    //扩展 lib 目录下的其它模块
    layui.each(setter.extend, function(index, item){
        var mods = {};
        mods[item] = '{/}' + setter.base + 'lib/extend/' + item;
        layui.extend(mods);
    });
    
    view().autoRender();
    
    function createMenuHtml(menu,html='') {
        //生成菜单
        $.each(menu,function(key,vo){
            //判断是否有下级别
            if (vo.children.length > 0) {
                html += '<li data-name="'+vo.id+'" class="layui-nav-item">\
                            <a href="javascript:;" lay-tips="'+vo.title+'" lay-direction="2">\
                                <i class="layui-icon '+vo.icon+'"></i>\
                                <cite>'+vo.title+'</cite>\
                            </a>\
                            <dl class="layui-nav-child">';
                
                html += createMenuHtml2(vo.children)
                
                html += '</dl></li>';
                return true;
            } else {
                html += '<li data-name="'+vo.id+'" class="layui-nav-item">\
                                <a href="'+vo.uri+'" lay-tips="'+vo.title+'" lay-direction="2" class="layui-this">\
                                    <i class="layui-icon '+vo.icon+'"></i>\
                                    <cite>'+vo.title+'</cite>\
                                </a>\
                             </li>';
            }
            
        });
        return html
    }
    
    function createMenuHtml2(menu,html='') {
        //生成菜单二级菜单
        $.each(menu,function(key,vo){
            if (vo.children.length > 0) {
                html += '<dd class="layui-nav-item">\
                                <a href="javascript:;"><i class="layui-icon '+vo.icon+'"></i>'+vo.title+'</a>\
                                <dl class="layui-nav-child">';
                
                html += createMenuHtml3(vo.children)
                
                html += '</dl></dd>';
                return true;
            } else {
                html += '<dd>\
                                <i class="layui-icon '+vo.icon+'"></i>\
                                <a lay-href="'+vo.uri+'">'+vo.title+'</a>\
                             </dd>';
            }
        });
        return html;
    }
    
    function createMenuHtml3(menu,html='') {
        $.each(menu,function(key,vo){
            html += '<dd><a lay-href="'+vo.uri+'"><i class="layui-icon '+vo.icon+'"></i>'+vo.title+'</a></dd>';
        });
        return html;
    }
    
    //加载公共模块
    layui.use('common');
    
    //对外输出
    exports('index', {
        openTabsPage: openTabsPage,
        //获取用户菜单 , 需要这样写 ， 外部才能调用
        _menu:_menu,
        getQueryVariable:getQueryVariable
    });
});
