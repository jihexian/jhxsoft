<?php
?>
    	<header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    店铺申请
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
        <div class="mgt68 apply-shop">
			<div class="weui-cells weui-cells_form">
	            <div class="weui-cell">
	                <div class="weui-cell__hd">
	                    <label class="weui-label"><em class="crf4 mgr10">*</em>店铺名</label>
	                </div>
	                <div class="weui-cell__bd">
	                    <input class="weui-input" type="text" placeholder="请输入店铺名">
	                </div>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__hd"><label class="weui-label"><em class="crf4 mgr10">*</em>店铺地址</label></div>
	                <div class="weui-cell__bd">
	                    <input class="weui-input" type="number" pattern="text" placeholder="请输入店铺地址">
	                </div>
	            </div>
	        </div>
	        <div class="weui-cells">
	        	<div class="weui-cell">
		            <div class="weui-cell__bd">
		                <div class="weui-uploader">
		                    <div class="weui-uploader__hd">
		                        <p class="weui-uploader__title"><em class="crf4 mgr10">*</em>请上传营业执照：</p>
		                    </div>
		                    <div class="weui-uploader__bd">
		                        <ul class="weui-uploader__files uploaderFiles">
		                        </ul>
		                        <div class="weui-uploader__input-box">
		                            <input class="weui-uploader__input uploaderInput" type="file" accept="image/*" multiple="">
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
	                        <p class="weui-uploader__title"><em class="crf4 mgr10">*</em>请上传身份证正反面：</p>
	                    </div>
	                    <div class="weui-uploader__bd">
	                        <ul class="weui-uploader__files uploaderFiles idCart">
	                        </ul>
	                        <div class="weui-uploader__input-box">
	                            <input class="weui-uploader__input uploaderInput" type="file" accept="image/*" multiple="">
	                        </div>
	                    </div>
	                </div>
	        	</div>
	        </div>
	        <div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary fs32" href="javascript:" id="showTooltips">提交</a>
            </div>
            <div class="weui-gallery" id="gallery">
                <span class="weui-gallery__img" id="galleryImg"></span>
                <div class="weui-gallery__opr">
                    <a href="javascript:" class="weui-gallery__del">
                    <i class="weui-icon-delete weui-icon_gallery-delete"></i>
                </a>
                </div>
            </div>
        </div>
        <?php
$this->registerJs(<<<JS
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
		                    var tp = tmpl.replace(/#spec_value#/, 'image_list[]');
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
	                var spec_value = 'shop[]';
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
	    });
JS
);
?>