
{include file="public/header" /}

<!-- 主体内容 -->
<div class="layui-body" id="LAY_app_body">
    <div class="right">
        <div class="title">运营人员目录</div>

        <div class="layui-form">
            <table class="layui-table" lay-size="sm">
                <colgroup>
                    <col>
                    <col>
                    <col width="100">
                </colgroup>
                <thead>
                <tr>
                    <th>文件名</th>
                    <th>运营人员</th>
                    <th class="tc">操作</th>
                </tr>
                </thead>
                <tbody>
                {foreach name="list" item="v"}
                <tr>
                    <td>{$v.user.username}</td>
                    <td>{$v.user.nickname}</td>
                    <td class="tc">
                        <a href="{:url('sku', ['id' => $v.user.id])}" class="layui-btn layui-btn-sm">打开</a>
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
