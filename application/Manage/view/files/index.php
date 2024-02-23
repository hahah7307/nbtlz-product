
{include file="public/header" /}

<!-- 主体内容 -->
<div class="layui-body" id="LAY_app_body">
    <div class="right">
        <a href="{$back_url}" class="layui-btn layui-btn-danger layui-btn-sm fr"><i class="layui-icon">&#xe603;</i>返回上一页</a>
        <div class="title">资源文件</div>
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

        <div class="layui-form">
            <button type="button" class="layui-btn  layui-btn-normal" id="uplaod">上传</button>
            <button style="display: none" id="excel"></button>
            <table class="layui-table" lay-size="sm">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col width="150">
                    <col width="100" ">
                    <col width="100">
                </colgroup>
                <thead>
                <tr>
                    <th>文件名称</th>
                    <th>文件目录</th>
                    <th>临时文件下载路径</th>
                    <th>过期时间</th>
                    <th>临时文件状态</th>
                    <th class="tc">操作</th>
                </tr>
                </thead>
                <tbody>
                {foreach name="list" item="v"}
                <tr>
                    <td>{$v.file_name}</td>
                    <td>{$v.file_path}</td>
                    <td><a href="{$v.file_tmp_url}">{$v.file_tmp_url}</a></td>
                    <td>{$v.file_tmp_expire}</td>
                    <td class="tc">
                        {if condition="empty($v.file_tmp_expire)"}
                            <span class="orange">未生成</span>
                        {elseif condition="$v.file_tmp_expire gt time()"/}</td>
                            <span class="red">已过期</span>
                        {else/}
                            <span class="green">未过期</span>
                        {/if}
                    <td class="tc">
                        <button class="layui-btn layui-btn-normal layui-btn-sm" data-id="{$v.id}" lay-submit lay-filter="formCoding">生成临时路径</button>
                    </td>
                </tr>
                {/foreach}
                </tbody>
            </table>
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

        // 上传
        let uploadInst = upload.render({
            elem: '#uplaod' //绑定元素
            ,url: '/Manage/Upload/file_upload' //上传接口
            ,done: function(res){
                //上传完毕回调
                if (res.code === 1) {
                    layer.alert(res.msg,{icon:1,closeBtn:0,title:false,btnAlign:'c',},function(){
                        location.reload();
                    });
                } else {
                    layer.alert(res.msg,{icon:2,closeBtn:0,title:false,btnAlign:'c'},function(){
                        layer.closeAll();
                        $('button').attr('disabled',false);
                    });
                }
            }
            ,error: function(){
                //请求异常回调
                layer.alert('操作异常',{icon:2,closeBtn:0,title:false,btnAlign:'c',},function(){
                    location.reload();
                });
            }
        });
    });
</script>

{include file="public/footer" /}
