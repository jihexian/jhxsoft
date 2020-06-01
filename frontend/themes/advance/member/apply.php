<?php
use yii\helpers\Url;
?>
<header class="top-fixed">
	<div class="weui-flex top-box">
		<div onclick="javascript:history.back(-1);">
			<i class="iconfont icon-fanhui"></i>
		</div>
		<div class="weui-flex__item">店铺申请</div>
		<div>
			<i class="iconfont icon-mulu" id="mulu-bt"></i>
		</div>
	</div>
</header>
<div class="mgt68 apply-shop">
<form id='shop' method="post">
	<div class="weui-cells weui-cells_form">
		<div class="weui-cell" style="margin-top: 6px;">
			<div class="weui-cell__hd">
				<label class="weui-label"><em class="crf4 mgr10">*</em>店铺名</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" name="name" type="text" placeholder="请输入店铺名">
			</div>
		</div>
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label"><em class="crf4 mgr10">*</em>店铺地址</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="text" pattern="text" name="address"
					placeholder="请输入店铺地址">
			</div>
		</div>
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label"><em class="crf4 mgr10">*</em>电话号码</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="text" <?php if(yii::$app->user->identity->mobile):?> readonly="readonly" <?php endif;?>value="<?=yii::$app->user->identity->mobile?>" pattern="text" name="mobile"
					placeholder="请输入电话号码">
			</div>
		</div>
		
       		<div class="weui-cell">
           
		
       		    <input type="text" class="weui-code" name="verifyCode" name="code"  placeholder="请输入验证码" >
      			<a id="verify"  onclick="sendcode(1)" class="weui-code-btn">获取验证码</a>
    		 
       		</div> 
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label"><em class="crf4 mgr10">*</em>行业选择</label>
			</div>
			<div class="weui-cell__bd">
				<input class="weui-input category" type="text" pattern="text" name="category_id"
					placeholder="请选择行业">
			</div>
		</div>
	</div>
	<div class="weui-cells">
		<div class="weui-cell">
			<div class="weui-cell__bd">
				<div class="weui-uploader">
					<div class="weui-uploader__hd">
						<p class="weui-uploader__title">
							<em class="crf4 mgr10">*</em>请上传营业执照：
						</p>
					</div>
					<div class="weui-uploader__bd">
						<ul class="weui-uploader__files uploaderFiles">
						</ul>
						<div class="weui-uploader__input-box">
							<input class="weui-uploader__input uploaderInput" type="file" 
								accept="image/*" multiple="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="weui-cells">
		<div class="weui-cell">
			<div class="weui-uploader">
				<div class="weui-uploader__hd">
					<p class="weui-uploader__title">
						<em class="crf4 mgr10">*</em>请上传身份证正反面：
					</p>
				</div>
				<div class="weui-uploader__bd">
					<ul class="weui-uploader__files uploaderFiles idCart">
					</ul>
					<div class="weui-uploader__input-box">
						<input class="weui-uploader__input uploaderInput" type="file" 
							accept="image/*" multiple="">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="weui-btn-area">
		<a class="weui-btn weui-btn_primary fs32" href="javascript:"
			id="showTooltips">提交</a>
	</div>
	<div class="weui-gallery" id="gallery">
		<span class="weui-gallery__img" id="galleryImg"></span>
		<div class="weui-gallery__opr">
			<a href="javascript:" class="weui-gallery__del"> <i
				class="weui-icon-delete weui-icon_gallery-delete"></i>
			</a>
		</div>
	</div>
</form>
</div>
<style>
.weui-cells_radio{
    height: 5rem;
}
</style>
<?php $this->beginBlock('block1') ?>  
 var countdown=60; 
            function sendcode(scene){      
                       
                    var obj = $("#verify");
                  
                    var mobile = $("input[name*='mobile']").val();  
                  
                    if(checkMobile()&obj.attr('disabled')!='disabled'){
                        $.ajax({
                           type:'post',
                           dataType:'json',
                           url:"/site/sms",
                           data:{'mobile':mobile,'scene':scene},
                           success:function(e){
                               settime(obj);                    
                           }
                        }) 
                    }           
                     
            }
              function settime(obj) { //发送验证码倒计时
                if (countdown == 0) { 
                    obj.attr('disabled',false); 
                    obj.html("获取验证码");
                    countdown = 60; 
                    return;
                } else { 
                    obj.attr('disabled',true);
                   
                    if(countdown<10){
                        obj.html("重新发送(0" + countdown + ")");
                    }else{
                         obj.html("重新发送(" + countdown + ")");
                    }
                    countdown--; 
            
                } 
            setTimeout(function() { 
                settime(obj) }
                ,1000) 
            }
            //手机号验证
            function checkMobile(){
             
                var mobile = $("input[name*='mobile']").val();
                var reg = /^1\d{10}$/;
                if(mobile.length===0){
                    mobileValid = false ;
                    $.alert("请输入11位手机号！");
                    return false;     
                }else if(mobile.length===11){ 
                    if(!reg.test(mobile)){
                        $.alert("手机号格式错误！");
                    }else{
                        return true;                           
                    }                    
                }else{
                    $.alert("请输入11位手机号！");
                    return false;     
                }
                
            }

       
   $(function(){
	        var tmpl = '<li class="weui-uploader__file" style="background-image:url(#url#)"><input class="weui-input" style="display: none" type="text" name="#spec_value#" value="#url#"></li>',
	            gallery = $("#gallery"), galleryImg = $("#galleryImg"),
	            uploaderInput = $(".uploaderInput");

	        $(".apply-shop").on("change",".uploaderInput", function(e){
	        	var src, url = window.URL || window.webkitURL || window.mozURL,files = e.target.files;
	            var i, j, len = files.length;
		        var pre_count = $(this).parent().siblings("ul").children("li").last().index();
		        var uploaderFiles = $(this).parent().siblings("ul");
		        var total_count = pre_count + len;
	        	if(uploaderFiles.hasClass("idCart")){
	        		if (total_count > 1){
		                return;
		            }else{
		                for (i = 0, j = pre_count; i < len, j < total_count; ++i, ++j) {
		                    var file = files[i];
		                    if (url) {
		                        src = url.createObjectURL(file);
		                    } else {
		                        src = e.target.result;
		                    }
		                    var src = funUpload(file);
		                    var tp = tmpl.replace(/#spec_value#/, "idcard["+i+"]");
		                    $(this).parents(".weui-uploader__bd").find(".uploaderFiles").append($(tp.replace(/#url#/g, src)));
		                }
		            }
        		}else{
        			var src, url = window.URL || window.webkitURL || window.mozURL, files = e.target.files;
	                var file = files[0];
	                if (url) {
	                    src = url.createObjectURL(file);
	                } else {
	                    src = e.target.result;
	                }
		            var src = funUpload(file);
	                var spec_value = "license";
	                var tp = tmpl.replace(/#spec_value#/, spec_value);
	                $(this).parents(".weui-uploader__bd").find(".uploaderFiles").html($(tp.replace(/#url#/g, src)));

        		}
                

	            
	        });

	        function funUpload(file) {
		        var result = '';
		        var fd = new FormData();
		        fd.append('file', file);
		        fd.append('fileparam', 'image');
		        fd.append('thumb', 1);
		        fd.append('height', '100');
		        fd.append('width', '100');
		        var xhr = new XMLHttpRequest();
		        xhr.onreadystatechange = function() {
		            if (xhr.readyState == 4 && xhr.status == 200) {
		                var data = JSON.parse(xhr.responseText);
		                var files = data.files;
		                result = files[0].url;
		            }
		        };
		        var link = "/api/upload/images-upload";
		        xhr.open('POST', link, false);
		        xhr.send(fd);
		        return result;
		    }

	        $(".apply-shop").on("click", ".uploaderFiles li", function() { //显示预览图
		        galleryImg.attr("style", this.getAttribute("style"));
		        gallery.fadeIn(100);
		    });

            //点击提交上传表单         
            $("#showTooltips").on("click", function() { 

                var flag = true;
		        //判断表单数据是否为空

                var shopForm = $('#shop').serializeArray();
                if(!shopForm.indexOf("#license")){
                    $.toast("请上传营业执照", "forbidden");
                    return;
                }
                if(!shopForm.indexOf(".idcard")){
                    $.toast("请上传身份证正反面", "forbidden");
                    return;
                }
              
                $.each(shopForm,function(i,item){
                    if(item['value'] == '') {
                        switch(item.name){
                            case 'name':$.toast("请输入店铺名", "forbidden");break;
                            case 'address':$.toast("请输入店铺地址", "forbidden");break;
                            case 'verifyCode':$.toast("请输入验证码", "forbidden");break;
                            case 'category_id':$.toast("请选择行业", "forbidden");break;
                            case 'idcard':$.toast("请上传身份证正反面", "forbidden");break;
                            case 'license':$.toast("请上传营业执照", "forbidden");break;
                            case 'mobile':$.toast("请填写电话号码", "forbidden");break;

                        }
                        flag = false;
                        return;
                    }
               });
                if(flag){
                    $.ajax({
                        type: 'post',
                        dataType:"json",
                        url: $("form").attr('action'),
                        data:$("form").serialize(),
                        success: function(e) {
                        if(e.status==0){
                          	$.toast(e.msg,'forbidden');
                          }else if(e.status==1){
                           $.toast(e.msg);
                           window.open('<?=Url::to(['/member/index'])?>','_self');
                          }
                        },
                       error: function(data) {
                        $.alert('系统出错');
                       }
                    });
                }

		    });
		    $(".apply-shop").on("click", "#gallery", function() { //关闭预览图
		        gallery.fadeOut(100);
		    });
		    $(".apply-shop").on("click", ".weui-icon_gallery-delete", function() {
		        var imgurl = $("#galleryImg").css("backgroundImage");
		        $(".weui-uploader__file").each(function(i){
		            var bgurl = $(this).css("backgroundImage");
		            if (imgurl == bgurl){
		                $(this).remove();
		                return;
		            }
		        });   
		    })

            $(".category").select({
              title: "选择行业",
              items: [
             	<?php foreach ($model as $v):?>
                {
                  title: "<?=$v['name']?>",
                  value: "<?=$v['id']?>",
                },
                <?php endforeach;?>
              ]
            });
	    });
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>  