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

    
    //管理员管理
    table.render({
        elem: '#LAY-list'
        ,page:true
        ,loading:true
        ,url: layui.setter.api_domain + api_list.AdminPermissionList
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
            ,{field: 'name', title: '权限名称'}
            ,{field: 'slug', title: '权限别名'}
            ,{field: 'http_method', title: '请求方式', toolbar: '#table-http_method'}
            ,{field: 'http_path', title: '请求路径', toolbar: '#table-http_path' }
            ,{field: 'created_at', title: '添加时间', sort: true}
            ,{field: 'updated_at', title: '修改时间', sort: true}
            ,{title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-operating'}
        ]]
        ,done: function() {
            $("td[data-field='http_path']").each(function(){
                $(this).find(".layui-table-cell").removeClass('layui-table-cell');
            });
            $("td[data-field='http_method']").each(function(){
                $(this).find(".layui-table-cell").removeClass('layui-table-cell');
            });
        }
        ,text: '对不起，加载出现异常！'
    });
    
    //监听工具条
    table.on('tool(LAY-list)', function(obj){
        var infodata = obj.data;
        if(obj.event === 'del'){
            layer.confirm('确定删除此数据？', function(index){
                admin.req({
                    url: layui.setter.api_domain + api_list.AdminPermissionDel
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
                ,title: '编辑'
                ,content: '../../../views/auth/permission/edit.html'
                // ,maxmin: true
                ,area: ['500px', '500px']
                ,btn: ['确定', '取消']
                ,yes: function(index, layero){
                    var iframeWindow = window['layui-layer-iframe'+ index]
                        ,submitID = 'LAY-submit'
                        ,submit = layero.find('iframe').contents().find('#'+ submitID);
                    //监听提交
                    iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                        var field = data.field; //获取提交的字段

                        //获取checkbox数据
                        quotation = new Array();
                        iframeWindow.layui.$("input:checkbox[name='http_method']:checked").each(function(){
                            quotation.push($(this).val());
                        });
                        field.http_method = quotation

                        //提交 Ajax 成功后，静态更新表格中的数据
                        admin.req({
                            url: layui.setter.api_domain + api_list.AdminPermissionSave
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
                    $.each(infodata,function(key,vo){
                        body.find("input[name="+key+"]").val(vo);
                    })
                    body.find("textarea[name=http_path]").val(infodata.http_path.join("\n"));
                    //元素更新必须使用,否则没有效果,在子页面进行render()渲染。
                },
                cancel: function(index, layero){
                    //关闭按钮进行刷新，否则下一个，无法进行渲染。
                    // $('#searchId').click();
                }
            })
        }
    });
    
    exports('auth_permission', {})
});