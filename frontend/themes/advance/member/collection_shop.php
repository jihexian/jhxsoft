<?php
use yii\helpers\Url;

?>
<header class="top-fixed">
	<div class="weui-flex top-box">
		<div onclick="javascript:history.back(-1);">
			<i class="iconfont icon-fanhui"></i>
		</div>
		<div class="weui-flex__item">收藏店铺</div>
		<div>
			<i class="iconfont icon-mulu" id="mulu-bt"></i>
		</div>
	</div>
</header>
<?=$this->render('../layouts/cart_menu')?>
<div class="main">
	<div class="gd-list">
	<?php if(!empty($model)):?>
	<?php foreach ($model as $v):?>
		<div class="bgfff mgt20">
			<div class="weui-media-box weui-media-box_appmsg">
				<a href="<?=Url::to(['/shop/index','shop_id'=>$v['shop']['id']])?>" class="weui-media-box__hd"> <img class="weui-media-box__thumb"
					src="<?=$v['shop']['logo']?>" alt="">
				</a>
				<div class="weui-media-box__bd">
					<a href="<?=Url::to(['/shop/index','shop_id'=>$v['shop']['id']])?>"><h4 class="weui-media-box__title fs28 lh34 h68"><?=$v['shop']['name']?></h4></a>
					<p>
						<span class="collect-btn" onclick="window.open('<?=Url::to(['/shop/index','shop_id'=>$v['shop']['id']])?>','_self');">进店</span>
						<i class="iconfont icon-shanchu in-block delete" id='<?=$v['shop']['id']?>'></i>
					</p>
				</div>
			</div>
		</div>
	<?php endforeach;?>
	<?php else: ?>
    <div class="null-data">
      <i class="iconfont icon-empty"></i>
      <div>还没有收藏店铺，先去逛逛吧~</div>
      <div class="gohome"><a href="<?=url::to(['/shop/lists'])?>">去逛逛</a></div>
    </div>
	<?php endif; ?>
	</div>
</div>
<?php $this->beginBlock('block1') ?>
	var save_flag = true;
	 $(".delete").click(function() {
	 	if(save_flag){
	 		var mythis = $(this);
	 		shop_id= $(this).attr("id");
	 		       $.ajax({
                   type: 'post',
                   dataType:"json",
                   url: "/collection/del-shop",
                   data: {shop_id:shop_id},
                   beforeSend: function(){
    		          	save_flag = false;
    				  },
                   success: function(e) {
                        if(e.status==1){	
        					$.toast('删除成功');
        					mythis.parent().parent().parent().remove();
                            save_flag = true;
    					}else{
    					   $.toast(e.msg, "forbidden");
                            save_flag = true;
    					}
                    },
                  error:function() {
                     $.toast("操作失败", "forbidden");
                    },
              	  })
        }
    });
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>  