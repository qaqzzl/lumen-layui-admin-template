/**
 
 @Name：layuiAdmin 用户管理 管理员管理 角色管理
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
 
 */


layui.define(['table', 'form'], function(exports){
    var $ = layui.$
        ,table = layui.table
        ,form = layui.form
        ,admin = layui.admin
        ,api_list = layui.setter.api_list
    var tableloading = layui.layer.open({       //表格第一次加载动画
        type:3
        ,offset: 't'
    });
    
    //管理员管理
    table.render({
        elem: '#LAY-list'
        ,page:true
        ,loading:true
        ,url: layui.setter.api_domain + api_list.AdminRoleList
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
            ,{field: 'name', title: '角色名称'}
            ,{field: 'slug', title: '角色别名'}
            ,{field: 'role_permission', title: '角色权限', toolbar: '#table-permission' }
            ,{field: 'created_at', title: '添加时间', sort: true}
            ,{field: 'updated_at', title: '修改时间', sort: true}
            ,{title: '操作', width: 250, align: 'center', fixed: 'right', toolbar: '#table-operating'}
        ]]
        ,done: function() {
            layui.layer.close(tableloading) ////表格第一次加载动画关闭
        }
        ,text: '对不起，加载出现异常！'
    });
    
    //监听工具条
    table.on('tool(LAY-list)', function(obj){
        var infodata = obj.data;
        if(obj.event === 'del'){
            layer.confirm('确定删除此管理员？', function(index){
                admin.req({
                    url: layui.setter.api_domain + api_list.AdminRoleDel
                    ,data:{ids:[obj.data.id]}
                    ,done: function(res){
                        obj.del();
                        layer.close(index);
                    }
                });

            });
        }else if(obj.event === 'edit'){
            layer.open({
                type: 2
                ,title: '编辑管理员'
                ,content: '../../../views/auth/role/edit.html'
                // ,maxmin: true
                ,area: ['500px', '350px']
                ,btn: ['确定', '取消']
                ,yes: function(index, layero){
                    var iframeWindow = window['layui-layer-iframe'+ index]
                        ,submitID = 'LAY-submit'
                        ,submit = layero.find('iframe').contents().find('#'+ submitID);
                    //监听提交
                    iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                        var field = data.field; //获取提交的字段
                        //提交 Ajax 成功后，静态更新表格中的数据
                        admin.req({
                            url: layui.setter.api_domain + api_list.AdminRoleSave
                            ,data:field
                            ,done: function(res){
                                table.reload('LAY-list'); //数据刷新
                                layer.close(index); //关闭弹层
                            }
                        });

                    });
                    
                    submit.trigger('click');
                }
                ,success: function(layero, index){
                    var body=layer.getChildFrame('body',index);
                    body.find("input[name=id]").val(infodata.id);
                    body.find("input[name=name]").val(infodata.name);
                    body.find("input[name=slug]").val(infodata.slug);
                    //元素更新必须使用,否则没有效果,在子页面进行render()渲染。
                },
                cancel: function(index, layero){
                    //关闭按钮进行刷新，否则下一个，无法进行渲染。
                    // $('#searchId').click();
                }
            })
        } else if(obj.event === 'role_permission'){       //管理员用户角色修改
            layer.open({
                type: 2
                ,title: '编辑管理员角色'
                ,content: '../../../views/auth/role/role_permission.html?role_permission='+encodeURIComponent(JSON.stringify(infodata.role_permission))
                // ,maxmin: true
                ,area: ['550px', '500px']
                ,btn: ['确定', '取消']
                ,yes: function(index, layero){
                    var iframeWindow = window['layui-layer-iframe'+ index]
                        ,submitID = 'LAY-submit'
                        ,submit = layero.find('iframe').contents().find('#'+ submitID);
                    var getData = iframeWindow.layui.transfer.getData('role_permission'); //获取右侧数据
                    var field = []
                    $.each(getData,function(key,vo){
                        field[key] = {
                            permission_id:vo.value,
                        }
                    });
                    admin.req({
                        url: layui.setter.api_domain + api_list.AdminRolePermissionSave
                        ,data:{id:infodata.id,permission_list:field}
                        ,done: function(res){
                            table.reload('LAY-list'); //数据刷新
                            layer.close(index); //关闭弹层
                        }
                    });

                    submit.trigger('click');
                }
            })
        }
    });
    
    exports('auth_role', {})
});