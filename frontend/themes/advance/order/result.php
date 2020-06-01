<?php

use yii\helpers\Url;

?>

    <div class="wrap">
        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                  查看原因
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
        <nav class="weui-tabbar mulu-con" id="mulu-con" style="position: relative;top:.68rem;display: none">
            <a href="index.html" class="weui-tabbar__item">
                <i class="iconfont icon-shouye"></i>
                <p class="weui-tabbar__label">首页</p>
            </a>
            <a href="fenlei.html" class="weui-tabbar__item">
                <i class="iconfont icon-leimupinleifenleileibie"></i>
                <p class="weui-tabbar__label">分类</p>
            </a>
            <a href="javascript:;" class="weui-tabbar__item">
                <i class="iconfont icon-sousuo"></i>
                <p class="weui-tabbar__label">搜索</p>
            </a>
            <a href="cart.html" class="weui-tabbar__item">
                 <i class="iconfont icon-06"></i>
                <p class="weui-tabbar__label">购物车</p>
            </a>
            <a href="center.html" class="weui-tabbar__item">
                <i class="iconfont icon-renwu"></i>
                <p class="weui-tabbar__label">我</p>
            </a>
        </nav>
        <div class="main">
       
	
		<div class="weui-panel weui-panel_access gd-list">
			<div class="weui-panel__hd fs32 lh48 cr333">商品详情</div>
			<div class="weui-panel__bd">
			<?php foreach ($data['orderSku'] as $v):?>
				<a href="javascript:void(0);"
					class="weui-media-box weui-media-box_appmsg">
					<div class="weui-media-box__hd">
						<img class="weui-media-box__thumb" src="<?=$v['sku_thumbImg']?>" alt="">
					</div>
					<div class="weui-media-box__bd">
						<h4 class="weui-media-box__title"><?=$v['goods_name']?></h4>
						<p class="weui-media-box__desc"><?=$v['sku_value']?></p>
						<p class="weui-media-box__desc"></p>
						<div class="weui-media-box__desc weui-media-box__bd__btn">
							<p>￥<?=$v['sku_sell_price_real']?>元</p><p>数量：<?=$v['num']?></p>
						</div>
					</div>
				</a> 
				<?php endforeach;?>	
			</div>
		
		</div>
		<div class="weui-cells gd-inf">
			
			<div class="weui-panel__hd fs32 lh48 cr333">申请理由</div>
				<div class="weui-cell__bd">
					<p class="weui-media-box"><?=$info['note']?></p>		
				</div>
			   
        </div>
        	<div class="weui-cells gd-inf">
		
				<div class="weui-panel__hd fs32 lh48 cr333">反馈结果</div>
		
				<div class="weui-cell__bd">
				 
					<p  class="weui-media-box"><?=$info['message']?></p>		
				
				</div>
		    
        </div>
    </div>
	<div class="weui-cells gd-inf ">
			<a href="<?=Url::to(['order/apply','order_id'=>$info['order_id']])?>" class="weui-btn weui-btn_warn">再次申请</a>
			<a href="javascript:history.back(-1);" class="weui-btn weui-btn_default">取消</a>
   </div>

 