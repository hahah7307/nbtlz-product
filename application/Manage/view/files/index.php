
{include file="public/header" /}

<!-- 主体内容 -->
<div class="layui-body" id="LAY_app_body">
    <div class="right">
        <a href="{$back_url}" class="layui-btn layui-btn-danger layui-btn-sm fr"><i class="layui-icon">&#xe603;</i>返回上一页</a>
        <div class="title">资源文件</div>
        {if condition="$role neq 'Painter'"}
        <form class="layui-form search-form" method="get">
            <div class="layui-inline w200">
                <input type="text" class="layui-input" name="keyword" value="{$keyword}" placeholder="">
            </div>
            <div class="layui-inline">
                <button class="layui-btn" lay-submit lay-filter="Search"><i class="layui-icon">&#xe615;</i> 查询</button>
            </div>
            <div class="layui-inline">
                <a class="layui-btn layui-btn-normal" href="{:url('index')}"><i class="layui-icon">&#xe621;</i> 重置</a>
            </div>
        </form>
        {/if}

        <div class="layui-form">
            {if condition="$role eq 'Super' or $role eq 'Painter'"}
                <button type="button" class="layui-btn  layui-btn-normal" id="uplaod" lay-submit lay-filter="upload">上传</button>
            {/if}
            {if condition="$role neq 'Painter'"}
            <table class="layui-table" lay-size="sm">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col width="180">
                    <col width="60">
                    <col width="100">
                    <col width="100">
                </colgroup>
                <thead>
                <tr>
                    <th>文件名称</th>
                    <th>临时文件下载路径</th>
                    <th>预览图</th>
                    <th>过期时间</th>
                    <th>状态</th>
                    <th>美工人员</th>
                    <th class="tc">操作</th>
                </tr>
                </thead>
                <tbody>
                {foreach name="list" item="v"}
                <tr>
                    <td>{$v.file_name}</td>
                    <td><a href="{$v.file_tmp_url}">{$v.file_tmp_url}</a></td>
                    <td><a href="{$v.file_tmp_url}"><img src="{$v.file_tmp_url}" alt="" style="height: 80px;object-fit: contain"></a></td>
                    <td>{$v.file_tmp_expire}</td>
                    <td class="tc">
                        {if condition="empty($v.file_tmp_expire)"}
                            <span class="orange">未生成</span>
                        {elseif condition="$v.file_tmp_expire gt time()"/}
                            <span class="red">已过期</span>
                        {else/}
                            <span class="green">未过期</span>
                        {/if}
                    </td>
                    <td>{$v.painter.nickname}</td>
                    <td class="tc">
                        <button class="layui-btn layui-btn-normal layui-btn-sm" data-id="{$v.id}" lay-submit lay-filter="formCoding">生成文件</button>
                    </td>
                </tr>
                {/foreach}
                </tbody>
            </table>
            {$list->render()}
            {/if}
        </div>
    </div>
</div>
<script>
    layui.use(['form', 'jquery', 'upload'], function(){
        let $ = layui.jquery,
            form = layui.form,
            upload = layui.upload;


        //监听提交
        form.on('submit(formCoding)', function(data){
            let text = $(this).text(),
                button = $(this),
                id = $(this).data('id');
            console.log(id);
            $('button').attr('disabled',true);
            button.text('请稍候...');
            axios.post("{:url('createTmpUrl')}", {id: id})
                .then(function (response) {
                    let res = response.data;
                    if (res.code === 1) {
                        layer.alert(res.msg,{icon:1,closeBtn:0,title:false,btnAlign:'c',},function(){
                            location.reload();
                        });
                    } else {
                        layer.alert(res.msg,{icon:2,closeBtn:0,title:false,btnAlign:'c'},function(){
                            layer.closeAll();
                            $('button').attr('disabled',false);
                            button.text(text);
                        });
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            return false;
        });

        //文件上传
        layui.use('upload', function () {
            //执行实例
            let uploadInst = upload.render({
                elem: '#uplaod',  //绑定元素
                accept: 'file',
                multiple: true,
                url: '/Manage/Upload/file_upload',  //上传接口
                before: function () {
                    //加载层-风格4
                    layer.msg('上传中，请勿关闭或刷新页面', {
                        icon: 16,
                        shade: 0.3,
                        time: 0
                    });
                },
                allDone: function(obj) {
                    setTimeout(function() {
                        // 这里是延迟执行的代码
                        layer.alert(obj.successful + '个文件已上传成功',{icon:1,closeBtn:0,title:false,btnAlign:'c',},function(){
                            location.reload();
                        });
                    }, 2000);
                },
                done: function(res){
                    //上传完毕回调
                    if (res.code === 1) {
                        // layer.alert(res.msg,{icon:1,closeBtn:0,title:false,btnAlign:'c',},function(){
                        //     location.reload();
                        // });
                        layer.msg('上传成功', {
                            time: 1500, //1.5秒关闭（如果不配置，默认是3秒）
                            shade: 0.3,
                        }, function() {
                            //加载层-风格4
                            layer.msg('上传中，请勿关闭或刷新页面', {
                                icon: 16,
                                shade: 0.3,
                                time: 0
                            });
                        });
                    } else {
                        layer.alert(res.msg,{icon:2,closeBtn:0,title:false,btnAlign:'c'},function(){
                            layer.closeAll();
                            $('button').attr('disabled',false);
                        });
                    }
                },
                error: function () {
                    //请求异常回调
                    layer.alert('操作异常',{icon:2,closeBtn:0,title:false,btnAlign:'c',},function(){
                        location.reload();
                    });
                }
            });
        });
    });
</script>

{include file="public/footer" /}
