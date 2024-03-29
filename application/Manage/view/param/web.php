
{include file="public/header" /}

<!-- 主体内容 -->
<div class="layui-body" id="LAY_app_body">
    <div class="right">
        <div class="title">基础设置</div>
		<div class="layui-form">
			<div class="layui-form-item">
				<label class="layui-form-label w120"></label>
				<div class="layui-input-inline w300">
					<input type="text" class="layui-input" name="aboutProduct" value="{$info.aboutProduct}" placeholder="请填写相关产品数">
				</div>
			</div>
            <br>
            <div class="title">阿里云OSS设置</div>
            <div class="layui-form-item">
                <label class="layui-form-label w120">Access Key Id</label>
                <div class="layui-input-inline w300">
                    <input type="text" class="layui-input" name="OSS_ACCESS_KEY_ID" value="{$info.OSS_ACCESS_KEY_ID}">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label w120">Access Key Secret</label>
                <div class="layui-input-inline w300">
                    <input type="text" class="layui-input" name="OSS_ACCESS_KEY_SECRET" value="{$info.OSS_ACCESS_KEY_SECRET}">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label w120">endpoint</label>
                <div class="layui-input-inline w300">
                    <input type="text" class="layui-input" name="OSS_ENDPOINT" value="{$info.OSS_ENDPOINT}">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label w120">bucket</label>
                <div class="layui-input-inline w300">
                    <input type="text" class="layui-input" name="OSS_BUCKET" value="{$info.OSS_BUCKET}">
                </div>
            </div>
			<div class="layui-form-item">
				<div class="layui-input-block">
					<button class="layui-btn w200" lay-submit lay-filter="formCoding">提交保存</button>
				</div>
			</div>
		</div>
    </div>
</div>
<script src="/static/manage/js/uploader-use.js"></script>
<script>
layui.use(['form', 'jquery'], function(){
	var $ = layui.jquery,
		form = layui.form;

	//监听提交
	form.on('submit(formCoding)', function(data){
		var text = $(this).text(),
			button = $(this);
		$('button').attr('disabled',true);
		button.text('请稍候...');
		$.ajax({
			type:'POST',url:"{:url('web')}",data:data.field,dataType:'json',
			success:function(data){
				if(data.code == 1){
					layer.alert(data.msg,{icon:1,closeBtn:0,title:false,btnAlign:'c'},function(){
						location.reload();
					});
				}else{
					layer.alert(data.msg,{icon:2,closeBtn:0,title:false,btnAlign:'c'},function(){
						layer.closeAll();
						$('button').attr('disabled',false);
						button.text(text);
					});
				}
			}
		});
		return false;
	});
});
</script>

{include file="public/footer" /}
