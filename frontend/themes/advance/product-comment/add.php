<?php

use yii\helpers\Url;

?>
        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    评价晒单
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
        <nav class="weui-tabbar mulu-con" id="mulu-con" style="position: relative;top:.68rem;display: none">
            <a href="<?php echo  Url::to(['site/index'])?>" class="weui-tabbar__item">
                    <i class="iconfont icon-shouye"></i>
                    <p class="weui-tabbar__label">首页</p>
            </a>
            <a href="<?php echo  Url::to(['product-type/index'])?>" class="weui-tabbar__item">
                    <i class="iconfont icon-leimupinleifenleileibie"></i>
                    <p class="weui-tabbar__label">分类</p>
            </a>
            <a href="<?php echo  Url::to(['cart/cart'])?>" class="weui-tabbar__item">
                     <i class="iconfont icon-06"></i>
                    <p class="weui-tabbar__label">购物车</p>
            </a>
            <a href="<?php echo  Url::to(['member/index'])?>" class="weui-tabbar__item">
                    <i class="iconfont icon-renwu"></i>
                    <p class="weui-tabbar__label">我</p>
            </a>
        </nav>
        <div class="main">
        <form id='add' method="post" action="/product-comment/add">
            <div class="eval">
                <div class="weui-flex eval-top">
                    <div class="img-box"><img src="<?=$model['sku_thumbImg']?>"></div>
                    <div class="weui-flex__item">
                        <p class="fs30">评分</p>
                        <p class="eval-xx">
                            <i class="iconfont icon-xingxing1"></i>
                            <i class="iconfont icon-xingxing1"></i>
                            <i class="iconfont icon-xingxing1"></i>
                            <i class="iconfont icon-xingxing1"></i>
                            <i class="iconfont icon-xingxing1"></i>
                        </p>
                    </div>
                </div>
                <div class="textarea-box">
                <input type="text"  id="id" value="<?=$model['id']?>" style="display: none">
                    <textarea class="weui-textarea" placeholder="评价" rows="3"></textarea>
                    <div class="weui-textarea-counter"><span>0</span>/200</div>
                </div>
                <div class="add-pic mgt30">
                    <div class="weui-uploader">
					<div class="weui-uploader__bd">
						<ul class="weui-uploader__files uploaderFiles">
						</ul>
						<div class="weui-uploader__input-box">
							<input class="weui-uploader__input uploaderInput" type="file" 
								accept="image/*" multiple="">
						</div>
					</div>
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
                <div class="fs26 cr888 mgt20" style="display: flex;align-items: center;">
                    <i class="sel-bt mgr20 sel-bt-active"></i> 匿名评价
                </div>
            </div>
            <div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary fs32" href="javascript:" id="showTooltips">提交</a>
            </div>
            </form>
        </div>
        
<?php $this->beginBlock('block1') ?>
   $(function(){
        $(".eval-xx i").click(function(){    
          var index = $(this).index();
           $(".eval-xx i").addClass("icon-xingxing2");
           for(var i = 0;i <= index;i ++){
              $(".eval-xx i").eq(i).removeClass("icon-xingxing2");
           } 
        })
        
	        var tmpl = '<li class="weui-uploader__file" style="background-image:url(#url#)"><input class="weui-input" style="display: none" type="text" name="#spec_value#" value="#url#"><input class="weui-input" style="display: none" type="text" name="#thumb_value#" value="#thumbUrl#"></li>',
	            gallery = $("#gallery"), galleryImg = $("#galleryImg"),
	            uploaderInput = $(".uploaderInput");

	        $(".main").on("change",".uploaderInput", function(e){
	        	var src, url = window.URL || window.webkitURL || window.mozURL,files = e.target.files;
	            var i=0;
	            var j=0; 
		        var len = $(".uploaderFiles").children().length;
		        
		        var total_count = 1;
				if (total_count >= 5){
	        		$.toast('最多上传5张评论图片','forbidden');
		       		return;
		        }else{
		        	console.log(i);
		        	var file = files[i];
		            if (url) {
		       			src = url.createObjectURL(file);
		            } else {
		        		src = e.target.result;
		           	}
		           	var src = funUpload(file);
		     		var tp = tmpl.replace(/#spec_value#/, "image["+len+"]");
		     		tp = tp.replace(/#url#/g, src['url']);
		     		tp = tp.replace(/#thumb_value#/, "thumbImg["+len+"]");
		     		tp = tp.replace(/#thumbUrl#/g, src['thumImg']);
		    		$(this).parents(".weui-uploader__bd").find(".uploaderFiles").append($(tp));		    		
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
		                result = files[0];
		            }
		        };
		        var link = "/api/upload/images-upload";
		        xhr.open('POST', link, false);
		        xhr.send(fd);
		        return result;
		    }
			var index;
	        $(".main").on("click", ".uploaderFiles li", function() { //显示预览图
	        	index = $(this).index();
		        galleryImg.attr("style", this.getAttribute("style"));
		        gallery.fadeIn(100);
		    });
     		gallery.on("click", function() {
            	gallery.fadeOut(100);
        	});
        //删除图片
        	$(".weui-gallery__del").click(function() {
            	$(".uploaderFiles").find("li").eq(index).remove();
            	$($(".uploaderFiles").find("li")).each(function(i){
                	$(this).find('input[name*="image"]').attr('name','image['+i+']');
                });
            	
        	});


      
            //点击提交表单
            
            var save_flag = true;
            $("#showTooltips").on("click", function() { 
            		if(save_flag){
            			var num;
                        num= $(".icon-xingxing1").length;
                        var id;
                        id=$('#id').val();
                        var content;
                        content=$('.weui-textarea').val();
                        var image = new Array();
                        $('input[name*="image"]').each(function(i){
                			image.push($(this).val());
                		});
            	            $.ajax({
                            type: 'post',
                            dataType:"json",
                            url: $("form").attr('action'),
                            data: {total_stars:num,id:id,content:content,image:image},
                            beforeSend: function(){
            		          	save_flag = false;
            				  },
                            success: function(e) {
                            	if(e.status==1){
									$.toast(e.msg);
									location.href ="list";
								}else{
									$.toast(e.msg, "forbidden");
								}
								save_flag = true;
                  			},
                  			error:function() {
                  				$.toast("操作失败", "forbidden");
                  				save_flag = true;
                  			},
          			      })
          		}
          	});
});

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>  
