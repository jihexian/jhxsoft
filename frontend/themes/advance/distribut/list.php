<?php
use yii\helpers\Url;
?>
        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    我的代理
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
        <?=$this->render('../layouts/cart_menu')?>
                <div class="main">
            <div class="wallet-box">
						<div class="ponit-mian">
						    <div class="distribut">
						    
								<div class="distribut-status">
									<p>用户</p>
								</div>
								<div class="distribut-status">
									<p>时间</p>
								</div>
							
							</div>
							<?php foreach ($model as $key=> $v):?>
							<div class="distribut-items">
							 
								<li>
									<?=$v['member']['name']?>
								</li>
								<li>
									<?=date('Y-m-d',$v['created_at'])?>
								</li>
							</div>		
							<?php endforeach;?>	
						</div>
						<?php if(count($model) == 0): ?>
					      <div class="null-data">
					        <i class="iconfont icon-Null-data"></i>
					        <div>您还没有代理，去分享您的链接吧</div>
					        <div class="gohome"><a href="<?=url::to(['/distribut/share'])?>">去分享</a></div>
					      </div>
					    <?php endif; ?>
            </div>

        </div> 
<style>
.distribut{
    background-color: #E7EAED;
}
</style>
<?php $this->beginBlock('block1') ?>
	<?php if($pages>1):?>
	var loading = false;  //状态标记
	<?php else:?>
	var loading = true;  //状态标记
	<?php endif;?>
	var index=$('.distribut>div').index(this)+2;
	var page=1;
    $(document.body).infinite().on("infinite", function() {
       
      if(loading) return;
      if(save_flag){
      		save_flag = false;
      		page++;
      		loading = true;
          	setTimeout(function() {
            loadlist(index);
          	}, 1500);   //模拟延迟
       }
    });

	var save_flag = true;
	$('.distribut>div').click(function(){
		if(save_flag){
			save_flag = false;
			loading=false;
			page=1;
    		$(this).addClass('active').siblings().removeClass('active');
            index=$('.distribut>div').index(this)+1;
            $(".log").remove();
            loadlist(index);
        }
	});
	
     function loadlist(index) {
           var html = "";
           $.ajax({
               type: "POST",
               url: "<?=Url::to(['distribut/member'])?>?page="+page,
               data: {'status': index },
               dataType: "json",
               beforeSend: function(){
               			loading=true;
    		          	html += '<div class="weui-loadmore">';
    					html += '<i class="weui-loading"></i>';
    					html += '<span class="weui-loadmore__tips">正在加载</span>';
    					html += '</div>';
    					$(".ponit-mian").append(html);
    				  },
    		  complete:function(XMLHttpRequest,textStatus){
                      // alert('远程调用成功，状态文本值：'+textStatus);
                     $(".weui-loadmore").empty();
          		 },
               error: function () {
 					save_flag = true;
               },
               success: function (data) {
               		if(data.pages==0){
               			loading=true;
               			    html += '<div class="log">';
    						html += '<div class="ponit-item" style="flex-direction: column;">';
    						html += '<div class="item-l">';
    						html += '<div class="item-l__tit">暂无记录^_^</div>';			
    						html += '</div>';
    						html += '</div>';	
    						html += '</div>';
               		}else if(data.pages <= page){
               			loading=true;
               		}else{
               			loading=false;
               		}
    					for (var i = 0; i < data.items.length; i++) {

    					    html +='<div class="distribut-items">';
    					    html +='<li>'+data.member[i].name+'</li><li>'+data.member[i].time+'</li></div>	';
                   		}
                   $(".ponit-mian").append(html);
                   save_flag = true;
               }
           });
    	}
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?> 