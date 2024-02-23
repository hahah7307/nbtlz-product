
{include file="public/header" /}

<!-- 主体内容 -->
<div class="layui-body" id="LAY_app_body">
    <div class="right">
        <a href="{$back_url}" class="layui-btn layui-btn-danger layui-btn-sm fr"><i class="layui-icon">&#xe603;</i>返回上一页</a>
        <div class="title">文件类型</div>
        <div class="layui-form">
            <table class="layui-table" lay-size="sm">
                <colgroup>
                    <col width="80">
                    <col>
                    <col width="100">
                </colgroup>
                <thead>
                <tr>
                    <th>类型ID</th>
                    <th>文件名称</th>
                    <th class="tc">操作</th>
                </tr>
                </thead>
                <tbody>
                {foreach name="list" item="v"}
                <tr>
                    <td>{$v.file_type}</td>
                    <td>{$v.name}</td>
                    <td class="tc">
                        <a href="{:url('index', ['sku' => $sku_id, 'type' => $v.id])}" class="layui-btn layui-btn-sm">打开</a>
                    </td>
                </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    layui.use(['form', 'jquery'], function(){
        let $ = layui.jquery,
            form = layui.form;

    });
</script>

{include file="public/footer" /}
