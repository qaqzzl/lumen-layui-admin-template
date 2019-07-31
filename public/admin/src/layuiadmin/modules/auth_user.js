/**
 
 @Name：layuiAdmin 用户管理 管理员管理 角色管理
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
 
 */


layui.define(['table', 'form'], function(exports){
    var $ = layui.$
        ,table = layui.table
        ,form = layui.form;
    
    //管理员管理
    table.render({
        elem: '#LAY-list'
        ,page:true
        ,loading:true
        ,url: layui.setter.api_domain + 'auth/admin.user'
        ,method:'post'
        ,parseData: function(res){ //res 即为原始返回的数据 , 解决后台数据不匹配问题
            return {
                "code": res.code, //解析接口状态
                "msg": res.msg, //解析提示文本
                "count": res.data.total, //解析数据长度
                "data": res.data.data //解析数据列表
            };
        }
        ,cols: [[
            {type: 'checkbox', fixed: 'left'}
            ,{field: 'id', width: 80, title: 'ID', sort: true}
            ,{field: 'avatar', title: '头像', templet:'avatarTpl'}
            ,{field: 'account', title: '登录名'}
            ,{field: 'nickname', title: '昵称'}
            // ,{field: 'role', title: '角色'}
            ,{field: 'created_at', title: '加入时间', sort: true}
            // ,{field: 'check', title:'审核状态', templet: '#buttonTpl', minWidth: 80, align: 'center'}
            ,{title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-operating'}
        ]]
        ,text: '对不起，加载出现异常！'
    });
    
    //监听工具条
    table.on('tool(LAY-list)', function(obj){
        var data = obj.data;
        if(obj.event === 'del'){
            layer.prompt({
                formType: 1
                ,title: '敏感操作，请验证口令'
            }, function(value, index){
                layer.close(index);
                layer.confirm('确定删除此管理员？', function(index){
                    console.log(obj)
                    obj.del();
                    layer.close(index);
                });
            });
        }else if(obj.event === 'edit'){
            var tr = $(obj.tr);
            
            layer.open({
                type: 2
                ,title: '编辑管理员'
                ,content: '../../../views/user/administrators/adminform.html'
                ,area: ['420px', '420px']
                ,btn: ['确定', '取消']
                ,yes: function(index, layero){
                    var iframeWindow = window['layui-layer-iframe'+ index]
                        ,submitID = 'LAY-user-back-submit'
                        ,submit = layero.find('iframe').contents().find('#'+ submitID);
                    
                    //监听提交
                    iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                        var field = data.field; //获取提交的字段
                        
                        //提交 Ajax 成功后，静态更新表格中的数据
                        //$.ajax({});
                        table.reload('LAY-user-front-submit'); //数据刷新
                        layer.close(index); //关闭弹层
                    });
                    
                    submit.trigger('click');
                }
                ,success: function(layero, index){
                
                }
            })
        }
    });
    
    exports('auth_user', {})
});