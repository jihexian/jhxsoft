<?php
?>
<div class="main mgt68">
	<header class="top-fixed">
		<div class="weui-flex top-box">
			<div onclick="javascript:history.back(-1);">
				<i class="iconfont icon-fanhui"></i>
			</div>
			<div class="weui-flex__item mgr9">个人信息</div>
			<div></div>
		</div>
	</header>
	<div class="weui-cells">
		<a class="weui-cell weui-cell_access" href="javascript:;" id="show-pic">
			<div class="weui-cell__bd weui-cell_primary">
				<p>头像</p>
			</div>
			<span class="weui-cell__ft">
					<ul class="weui-uploader__files" id="uploaderFiles">
						<li class="weui-uploader__file fnone" style="background-image: url(<?=$model['avatarUrl']?>)"></li>
					</ul>
			</span>
			<input id="uploaderInput" class="weui-uploader__input" type="file" accept="image/*" multiple="">
		</a>
		<a class="weui-cell weui-cell_access" href="javascript:;" id="name">
			<div class="weui-cell__hd"></div>
			<div class="weui-cell__bd weui-cell_primary">
				<p>昵称</p>
			</div>
			<span class="weui-cell__ft name"><?=$model['username']?></span>
		</a>
		<a class="weui-cell weui-cell_access" href="javascript:;" >
			<div class="weui-cell__hd"></div>
			<div class="weui-cell__bd weui-cell_primary">
				<p>绑定手机号</p>
			</div>
			<input class="weui-cell__ft input" type="text" id="mobile" value="<?= !empty($model['mobile'])? $model['mobile']:'未绑定'?>">
		</a>
		<a class="weui-cell weui-cell_access" href="javascript:;" >
			<div class="weui-cell__hd"></div>
			<div class="weui-cell__bd weui-cell_primary">
				<p>性别</p>
			</div>
			<input class="weui-cell__ft input" type="text" id="sex" value="<?=$model['sex']?$model['sex']:'保密'?>">
		</a>
		<a class="weui-cell weui-cell_access" href="javascript:;">
			<div class="weui-cell__hd"></div>
			<div class="weui-cell__bd weui-cell_primary">
				<p>出生日期</p>
			</div>
<!-- 			<span class="weui-cell__ft">2013-05-01</span> -->
			<input class="weui-cell__ft input" type="text" id='datetime-picker' value="<?=date("Y-m-d",$model['age'])?> &nbsp;"/>
		</a>
	</div>
</div>
<style>
.input{
    border: none;
}
.weui-picker-modal{
    height: auto;
}
</style>
<?php $this->beginBlock('block1') ?> 
	$(function(){

        var tmpl = '<li class="weui-uploader__file fnone" style="background-image:url(#url#)"></li>',
            $gallery = $("#gallery"), $galleryImg = $("#galleryImg"),
            $uploaderInput = $("#uploaderInput"),
            $uploaderFiles = $("#uploaderFiles")
            ;

        $uploaderInput.on("change", function(e){
            var src, url = window.URL || window.webkitURL || window.mozURL, files = e.target.files;
            for (var i = 0, len = files.length; i < len; ++i) {
                var file = files[i];

                if (url) {
                    src = url.createObjectURL(file);
                } else {
                    src = e.target.result;
                }
                $uploaderFiles.html($(tmpl.replace('#url#', src)));
            }
            var src = funUpload(file);   
            
                   $.ajax({
                   type: 'post',
                   dataType:"json",
                   url: "/member/info",
                   data: {avatarUrl:src},
                   success: function(e) {
						$.toast(e.msg);
                    },
                  error:function() {
                     $.toast("操作失败", "forbidden");
                    },
                 })
            
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
		  $("#name").click(function() {
		    $.prompt({
              title: '修改用户名',
              input: $(".name").text(),
              empty: false, // 是否允许为空
              onOK: function (input) {
                console.log(input);
                
                $.ajax({
                   type: 'post',
                   dataType:"json",
                   url: "/member/info",
                   data: {username:input},
                   success: function(e) {
                  	 $(".name").text(input);
						$.toast(e.msg);
                    },
                  error:function() {
                     $.toast("操作失败", "forbidden");
                    },
                 })
              },
              onCancel: function () {
                //点击取消
              }
            });
           });
           
           $("#sex").select({
              title: "选择职业",
              items: ["男", "女", "保密"],
              onClose: function() {
              	var sex=$("#sex").val();
            	$.ajax({
                   type: 'post',
                   dataType:"json",
                   url: "/member/info",
                   data: {sex:sex},
                   success: function(e) {
						$.toast(e.msg);
                    },
                  error:function() {
                     $.toast("操作失败", "forbidden");
                    },
                 })
       		  },
            });
          $("#datetime-picker").datetimePicker({
          		title:'请选择日期',
          		times: function() {
                    return [{
                        values: ['&nbsp;']
                    }]
                },
                onClose: function() {
              		var time=$("#datetime-picker").val();
              		console.log(time);
              		date = new Date(time.replace(/\s+/g,""));
              		date=date.getTime();
                  date=date/1000;
              		$.ajax({
                       type: 'post',
                       dataType:"json",
                       url: "/member/info",
                       data: {age:date},
                       success: function(e) {
    						$.toast(e.msg);
                        },
                      error:function() {
                         $.toast("操作失败", "forbidden");
                        },
                     })
              	
       		    },
          });

    });
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>   