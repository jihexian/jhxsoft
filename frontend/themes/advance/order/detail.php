<?php
use common\helpers\Tools;
use common\helpers\Util;
use yii\helpers\Url;


 ?>
 <style type="text/css">
 	.gd-inf-bt a{padding: .2rem .2rem;font-size: .3rem;}
 	.gd-inf-bt a:first-child{margin-right: .3rem;}
 </style>
<div class="wrap">
	<header class="top-fixed">
		<div class="weui-flex top-box">
			<div onclick="javascript:history.back(-1);">
				<i class="iconfont icon-fanhui"></i>
			</div>
			<div class="weui-flex__item">订单详情</div>
			<div>
				<i class="iconfont icon-mulu" id="mulu-bt"></i>
			</div>
		</div>
	</header>
<?=$this->render('../layouts/cart_menu')?>
	<div class="main" style="margin-top: 1rem;">
		<div class="weui-cells">
		<a class="weui-cell weui-cell_access" href="javascript:;">
			
				<div class="weui-cell__bd">
					<p class="fs32 cr333"><?=$data['full_name']?>&nbsp;&nbsp;<?=$data['tel']?></p>
			        <p class="fs24 cr999">配送方式：<?=Tools::getDelivery($data['delivery_id'])?></p>
			        <?php if($data['delivery_id']==2):?>
					<p class="fs24 cr999">自提点：<?=$pick['pick']['province']['name'].$pick['pick']['city']['name'].$pick['pick']['info']?></p>
					<?php else:?>
	             	<p class="fs24 cr999">地址：<?=$data['province']['name'].$data['city']['name'].$data['region']['name'].$data['address']?></p>
	             	<?php endif;?>
				</div>
	
			</a>
		</div>
		<div class="weui-panel weui-panel_access gd-list">
			<div class="weui-panel__hd fs32 lh48 cr333">商品列表</div>
			<div class="weui-panel__bd">
			<?php foreach ($data['orderSku'] as $v):?>
				<a href="<?=Url::to(['product/detail','id'=>$v['goods_id']])?>" class="weui-media-box weui-media-box_appmsg">
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
			<div class="weui-panel__ft">
				<a href="javascript:void(0);"
					class="weui-cell weui-cell_access weui-cell_link">
					<div class="weui-cell__bd fs24 lh38">查看更多</div> <span
					class="weui-cell__ft"></span>
				</a>
			</div>
		</div>
		<div class="weui-cells gd-inf">
			<a class="weui-cell" href="javascript:;">
				<div class="weui-cell__bd">
					<p>订单编号：<?=$data['order_no']?></p>
					<p>下单时间：<?=date("Y-m-d H:i:s",$data['create_time'])?></p>
				</div>
			</a> 
			 <?php if($data['payment_status']!=0):?>
			   <a class="weui-cell" href="javascript:;">
				<div class="weui-cell__bd">
					<p>支付方式：<?=$data['payment_name']?></p>
				</div>
			   </a>
			<?php endif;?>
		    <?php if($data['delivery_status']!=0):?>
			<a class="weui-cell" href="javascript:;">
				<div class="weui-cell__bd">
					<p>配送方式：<?=$data['orderDeliveryDoc']['shipping_name']?></p>
					<p>订单号：<?=$data['orderDeliveryDoc']['delivery_code']?></p>
					<p>发货日期：<?=date('Y-m-d H:i:s',$data['orderDeliveryDoc']['addtime'])?></p>
				</div>
			</a>
		   <?php endif;?>
		  <?php if($data['taxpayer']!=''):?>
		   <a class="weui-cell" href="javascript:;">
				<div class="weui-cell__bd">
					<p>发票类型：电子普通发票</p>
					<p>发票抬头：<?=$data['invoice_title']?></p>
					<p>发票抬头：<?=$data['taxpayer']?></p>
					<p>发票内容：商品明细</p>
				</div>
			</a>
		  <?php endif;?>
		    <a class="weui-cell" href="javascript:;">
		  	<div class="weui-cell__bd">
					<p>备注留言：<?=$data['m_desc']?></p>
				</div>
			</a>	
		</div>
		<div class="weui-cells gd-inf">
			<div class="weui-cell">
				<div class="weui-cell__bd">
					<p>商品金额</p>
					<p>运费</p>
					<?php if(!empty($data['coupons_price'])):?>
					<p>优惠券</p>
					<?php endif;?>
				</div>
				<div class="weui-cell__ft">
					<p class="crred">￥<?=$data['sku_price_real']?></p>
					<p class="crred">￥<?='+'.$data['delivery_price_real']?></p>
					<?php if(!empty($data['coupons_price'])):?>
					<p class="crred">￥<?='-'.$data['coupons_price']?></p>
					<?php endif;?>
				</div>
			</div>
			<div class="weui-cell" href="javascript:;">
				<div class="weui-cell__ft weui-flex__item">
					<p class="cr333">
						实付款：<em class="crred">￥<?=$data['pay_amount']?></em>
					</p>
				</div>
			</div>
		</div>
		<div class="gd-inf-bt">
			<div class="tr">
				<?=Tools::get_status_bottom($data['status'],$data['id'],$data['parent_sn']) ?>
			</div>
		</div>
	</div>
</div>
<?php 
$this->registerJs(<<<JS
	 //取消订单
    $(".main").on('click','.cancel',function(e){
        var href = $(this).data('href');
        cancel(href);
    });
    function cancel(href){
      $.confirm("您确定要取消订单吗？", function() {
         window.location.href = href;
        }, function() {
         
        }
      );
    }
    //删除订单
    $(".main").on('click','.del_order',function(e){
        var href = $(this).data('href');
        del(href);
    });
    function del(href){
      $.confirm("您确定要删除订单吗？", function() {
         window.location.href = href;
        }, function() {
         
        }
      );
    }
    //申请退款
    $(".main").on('click','.refund',function(e){
        var href = $(this).data('href');
        refund(href);
    });
    function refund(href){
      $.confirm("您确定要申请退款吗？", function() {
         window.location.href = href;
        }, function() {
         
        }
      );
    }
JS
);
?>   
