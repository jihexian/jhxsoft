<?php
use yii\helpers\Url;
?>
        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    分销商中心
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
        <?=$this->render('../layouts/cart_menu')?>
        <div class="main">
            <div class="wallet-box">
                    <div class="wallet-top">
                        <div class="tc">
                            <p class="fs28">累计获得赏金</p>
                            <p class="yue">￥<?=$member['distribut_money']?></p>
                        </div>
                    </div>
					<div class="weui-cells">

							<a class="weui-cell weui-cell_access" href="member/withdrawal">
								<div class="weui-cell__bd weui-cell_primary">
									<p>可提现赏金：<font color="red"><?=$money?></font> 元</p>
								</div>
								<span class="weui-cell__ft">立即提现</span>
							</a>
							<a class="weui-cell weui-cell_access" href="<?=Url::to(['/distribut/list'])?>">
								<div class="weui-cell__hd"></div>
								<div class="weui-cell__bd weui-cell_primary">
									<p>我的代理：<font color="red"><?=$num?></font></p>
								</div>
								<span class="weui-cell__ft">查看代理</span>
							</a>
						</div>
						<div class="demos-content-padded">
							<a href="<?=Url::to(['/distribut/share'])?>" class="weui-btn weui-btn_primary">分享给朋友</a>
						</div>	
						<div class="ponit-mian">
							<div class="distribut">
								<div class="distribut-status active">
									<p>已获得(份)</p>
									<span><?=$lognum[1]?></span>
								</div>
								<div class="distribut-status">
									<p>赏金在路上(份)</p>
									<span><?=$lognum[2]?></span>
								</div>
								<div class="distribut-status">
									<p>失败(份)</p>
									<span><?=$lognum[3]?></span>
								</div>
							</div>
							<?php if(empty($model)):?>
                			<div class="log">
                				<div class="ponit-item" style="flex-direction: column;">
                					<div class="item-l">
                						<div class="item-l__tit">暂无记录^_^</div>
                					</div>
                				</div>
                			</div>
                			<?php else:?>
							<?php foreach ($model as $v):?>
							<div class="log">
								<div class="ponit-item">
									<div class="item-l">
										<div class="item-l__tit"><?=$v['member']['name']?></div>
										<div class="item-l__time"><?=date('Y-m-d H:m:s',$v['updated_at'])?></div>
									</div>
									<div class="item-r crf4">
									<?='+'.$v['change_money']?></div>
								</div>				
							</div>
							<?php endforeach;?>
							<?php endif;?>
						</div>
            </div>
        </div>
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
               url: "<?=Url::to(['distribut/ajax'])?>?page="+page,
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
    						html += '<div class="log">';
    						html += '<div class="ponit-item">';
    						html += '<div class="item-l">';
    						html += '<div class="item-l__tit">'+data.member[i].name+'</div>';
    						html += '<div class="item-l__time">'+data.member[i].time+'</div>';
    						html += '</div>';
    						html += '<div class="item-r crf4">+'+data.items[i].change_money+'</div>';
    						html += '</div>';	
    						html += '</div>';
                   		}
                   $(".ponit-mian").append(html);
                   save_flag = true;
               }
           });
    	}
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?> 
