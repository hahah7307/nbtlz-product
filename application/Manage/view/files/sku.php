
{include file="public/header" /}

<!-- 主体内容 -->
<div class="layui-body" id="LAY_app_body">
    <div class="right">
        {if condition="$role eq 'Super' or $role eq 'Painter'"}
            <a href="{:url('seller')}" class="layui-btn layui-btn-danger layui-btn-sm fr"><i class="layui-icon">&#xe603;</i>返回上一页</a>
        {/if}
        <div class="title">Sku目录</div>
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
            <table class="layui-table" lay-size="sm">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    {if condition="$role eq 'Super'"}
                        <col width="100">
                    {/if}
                    <col width="100">
                </colgroup>
                <thead>
                <tr>
                    <th>目录名称</th>
                    <th>Sku</th>
                    <th>中文名称</th>
                    {if condition="$role eq 'Super'"}
                        <th>运营人员</th>
                    {/if}
                    <th class="tc">操作</th>
                </tr>
                </thead>
                <tbody>
                {foreach name="list" item="v"}
                <tr>
                    <td>{$v.sku_file_name}</td>
                    <td>{$v.product_sku}</td>
                    <td>{$v.product_name_cn}</td>
                    {if condition="$role eq 'Super'"}
                        <td>{$v.user.nickname}</td>
                    {/if}
                    <td class="tc">
                        <a href="{:url('type', ['id' => $v.id])}" class="layui-btn layui-btn-sm">打开</a>
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
