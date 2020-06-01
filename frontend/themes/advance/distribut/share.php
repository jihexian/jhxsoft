<?php

use yii\helpers\Url;
?>
        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                   分享给朋友
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
        <?=$this->render('../layouts/cart_menu')?>
        <div class="main" style="margin-top: .8rem;">
            <div style="background: #fff;padding: 10px 2%">将二维码或复制链接分享给微信好友，好友通过您的分享进入系统即可成为您的下级。</div>
            <div class="wallet-box">
            	<div class="qrcode">
            		<img  src="<?=Url::to(['/distribut/qrcode','url'=>$url])?>">
            	 </div>
            	 <div class="demos-content-padded">
					<a href="javascript:;" id="down1" download="downImg" class="weui-btn weui-btn_primary down">保存图片</a>
					<a href="javascript:;" class="weui-btn weui-btn_primary copy">复制链接</a>
				</div>
             </div>
        </div>
<style>
.wallet-box{
    /*background: url(/storage/images/share-bg.png);*/
    min-height: 80%;
    background-size: contain;
}
.qrcode{
    display: inline-block;
    width: 4rem;
    margin-left: 24%;

}
</style>
<?php $this->beginBlock('block1') ?>
    	$('.copy').click(function(){
    		var url="<?=env('SITE_URL').'/?pid='.\Yii::$app->user->id?>";
    		console.log(url);
        	var flag = copyText(url);//这个必须在DOM对象的事件线程中执行
            $.alert({
            	title:'',
            	text:flag ? "推广链接已复制，可以分享给微信、QQ、微博好友！" : "复制失败！"
            });
    	})
    	
    	//复制链接
    	function copyText(text) {
        var textarea = document.createElement("textarea");
        var currentFocus = document.activeElement;
        document.body.appendChild(textarea);
        textarea.value = text;
        textarea.focus();
        if (textarea.setSelectionRange)
            textarea.setSelectionRange(0, textarea.value.length);
        else
            textarea.select();
        try {
            var flag = document.execCommand("copy");
        } catch(eo){
            var flag = false;
        }
        document.body.removeChild(textarea);
        currentFocus.focus();
        return flag;
  	  }
  	  $().ready(function(){  
        html2canvas($(".wallet-box"), {  
            height: $(".wallet-box").outerHeight() + 20,  
                width: $(".wallet-box").outerWidth() + 20  ,
                onrendered: function(canvas) {
                //将canvas画布放大若干倍，然后盛放在较小的容器内，就显得不模糊了
                var timestamp = Date.parse(new Date());
                //把截取到的图片替换到a标签的路径下载  
                $("#down1").attr('href',canvas.toDataURL());  
                //下载下来的图片名字  
                $("#down1").attr('download',timestamp + '.png') ;   
                //document.body.appendChild(canvas);  
            }  
            //可以带上宽高截取你所需要的部分内容 
        });  
    });  


<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?> 
