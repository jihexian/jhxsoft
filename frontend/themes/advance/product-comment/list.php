<?php
use yii\helpers\Url;

?>
<div class="wrap">
	<header class="top-fixed">
		<div class="weui-flex top-box">
			<div onclick="javascript:history.back(-1);">
				<i class="iconfont icon-fanhui"></i>
			</div>
			<div class="weui-flex__item">可评论列表</div>
			<div>
				<i class="iconfont icon-mulu" id="mulu-bt"></i>
			</div>
		</div>
	</header>
<div class="main" >
		<div class="weui-panel weui-panel_access gd-list">
			<div class="weui-panel__hd fs32 lh48 cr333">商品列表</div>
			<div class="weui-panel__bd">
				<?php if(!empty($model)): ?>
				<?php foreach ($model as $v):?>
				<a href="javascript:void(0);"
					class="weui-media-box weui-media-box_appmsg">
					<div class="weui-media-box__hd">
						<img class="weui-media-box__thumb" src="<?=$v['sku_thumbImg']?>" alt="">
					</div>
					<div class="weui-media-box__bd">
						<h4 class="weui-media-box__title"><?=$v['goods_name']?></h4>
						<p class="weui-media-box__desc"><?=$v['sku_value']?></p>
						<p class="weui-media-box__desc"></p>	
						<span style="float: right;background-color: #04BE02;color: #fff;padding: .1rem;border-radius: 19px;"
						onclick="window.open('<?=Url::to(['/product-comment/add','id'=>$v['id']])?>','_self');">
						立即评价</span>
					</div>
				</a> 
				<?php endforeach;?>
				<?php else: ?>
			    <div class="null-data">
			      <i class="iconfont icon-Null-data"></i>
			      <div>没有对应的订单，赶紧去下单吧！</div>
			      <div class="gohome"><a href="<?=url::to(['/site/index'])?>">去下单</a></div>
			    </div>
			  <?php endif; ?>
			</div>
		</div>
</div>
</div>