<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年12月25日 下午5:44:14
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;

?>
<header class="top-fixed">
	<div class="weui-flex top-box">
		<div onclick="javascript:history.back(-1);">
			<i class="iconfont icon-fanhui"></i>
		</div>
		<div class="weui-flex__item">收藏商品</div>
		<div>
			<i class="iconfont icon-mulu" id="mulu-bt"></i>
		</div>
	</div>
</header>
<?=$this->render('../layouts/cart_menu')?>
<div class="main">
	<div class="gd-list">
	<?php if(!empty($collections)): ?>
	<?php foreach ($collections as $key=>$vo):?>
		<div class="bgfff mgt20 list<?=$vo['product']['product_id']?>">
			<div class="weui-media-box weui-media-box_appmsg" >
				<a class="weui-media-box__hd" href="<?=Url::to(['/product/detail','id'=>$vo['product']['product_id']])?>"> <img class="weui-media-box__thumb"
					src="<?=$vo['product']['image'][0]['thumbImg']?>" alt="">
				</a>
				<div class="weui-media-box__bd">
					<a href="<?=Url::to(['/product/detail','id'=>$vo['product']['product_id']])?>"><h4 class="weui-media-box__title fs28 lh34 h68"><?=$vo['product']['name']?></h4></a>
					<p class="weui-media-box__desc cr04BE02 fs34">￥<?=$vo['product']['min_price']?>元
					<i class="iconfont icon-shanchu in-block delete" id='<?=$vo['product']['product_id']?>'></i>
					</p>
				</div>
			</div>
		</div>
	<?php endforeach;?>
	<?php else: ?>
	    <div class="null-data">
	      <i class="iconfont icon-empty"></i>
	      <div>还没有收藏商品，先去逛逛吧~</div>
	      <div class="gohome"><a href="<?=url::to(['/product/index'])?>">去逛逛</a></div>
	    </div>
	<?php endif; ?>
	</div>
</div>
<?php $this->beginBlock('block1') ?>
	var save_flag = true;
	 $(".delete").click(function() {
	 	if(save_flag){
	 		var mythis = $(this);
	 		product_id= $(this).attr("id");
	 		       $.ajax({
                   type: 'post',
                   dataType:"json",
                   url: "/collection/del",
                   data: {product_id:product_id},
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