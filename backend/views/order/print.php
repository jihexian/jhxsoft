<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=Html::cssFile('@web/css/order.css')?>
<div class="box box-primary">
    <div class="box-body">
	    	<div class="print-container">
	    		<h3 class="title">订单详情</h3>
	    		<div class="print-box">
	    			<div class="info">
	        			<dl>
	        				<dt>收货人信息</dt>
	        				<dd></dd>
	        				<dt></dt>
	        				<dd></dd>
	        				<dt>下单时间：</dt>
	        				<dd><?php echo date('Y-m-d H:i:s',$model['create_time'])?></dd>
	        			</dl>
	        			<dl>
	        				<dt>收件人：</dt>
	        				<dd><?=$model['full_name']?></dd>
	        				<dt>联系电话: </dt>
	        				<dd><?=$model['tel']?></dd>
	        				<!-- <dt>邮编：</dt>
	        				<dd>535000</dd> -->
	        			</dl>
	        			<dl>
	        				<dt>收货地址：</dt>
	        				<dd><?=$model['province']['name']?>,<?=$model['city']['name']?>,<?=$model['region']['name']?>,<?=$model['address']?></dd>
	        			</dl>
	        		</div>
	    			<div class="product-info">
			            <h3>商品信息</h3> 
			            <table class="table table-bordered table-hover table-responsive">
			                <thead>
			                    <tr>
			                        <th>商品</th>
			                        <th>规格属性</th>
			                        <th>数量</th>
			                        <th>单品价格</th>
			                        <th>会员折扣价</th> 
			                        <th>单品小计</th>                                        
			                    </tr>
			                    </thead>
			                     <tbody class="list">
                        <?php foreach ($model['orderSku'] as $vo): ?>
                            <tr>                                     
                                <th><?=$vo['goods_name']?></th>
                                <th><?=$vo['sku_value']?></th>
                                <th><?=$vo['num']?></th>
                                <th><?=$vo['sku_market_price']?></th>
                                <th><?=$vo['sku_sell_price_real']?></th>
                                <th><?php echo($vo['sku_sell_price_real']*$vo['num']);?></th>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
			            </table>
	                	<div class="total">订单总额：￥<?=$model['sku_price_real']?></div>
	            	</div>
	    		</div>
	    		<div class="print"><a class="btn btn-primary btn-flat btn-xs" href="javascript:window.print();"><i class="fa fa-print"></i>打印订单</a></div>
	        </div>
    </div>
</div>
