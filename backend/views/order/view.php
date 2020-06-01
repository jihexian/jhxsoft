<?php
/**
 * Author wsyone wsyone@faxmail.com
 * Time:2019年11月27日下午5:33:27
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\Tools;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\ListView;
/* @var $this yii\web\View */
/* @var $model common\models\Order */
$this->title =Yii::t('common', 'Order Info');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=Html::cssFile('@web/css/order.css')?>
<div class="box box-primary">
    <div class="box-body">
        <div class="print"><a class="btn btn-primary btn-flat" href="<?php echo Url::toRoute(['order/print','id'=>$model['id']]);?>"><i class="fa fa-print"></i>打印订单</a></div>
        <div class="order-container">
            <div class="list-item row">
                <h3>基本信息</h3> 
                <div class="col-md-3">
                  <div><label>订单ID：</label><span><?=$model['id']?></span></div>
                  <div><label>订单号：</label><span><?=$model['order_no']?></span></div>
                  <div><label>会员：</label><span><?php echo Tools::get_user_name($model['m_id']); ?></span></div>
                <!--  <div><label>E-Mail:</label><span></span></div>  -->

                </div>
                <div class="col-md-3">
               <!--   <div><label>电话：</label><span>18277779458</span></div>-->
                  <div><label>应付金额：</label><span><?=$model['pay_amount']?></span></div>
                  <div><label>订单状态：</label><span><?= Tools::get_status($model['status'])?>/ <?= Tools::pay_status($model['payment_status'])?> / <?=  Tools::shipping_status($model['delivery_status'])?></span></div> 
                  <div><label>下单时间：</label><span><?php echo date('Y-m-d H:i:s',$model['create_time'])?></span></div>
                </div>
                <div class="col-md-6">
                    <?php if (!empty($model['payment_status'])): ?>
                  <div><label>支付时间：</label><span><?php echo date('Y-m-d H:i:s',$model['paytime'])?></span></div>
                  <div><label>支付方式：</label><span><?=$model['payment_name']?></span></div>
                   <?php endif; ?>

                  <div><label>用户备注：</label><span><?=$model['m_desc']?></span></div>
                </div>
            </div>
              <div class="list-item row">
                <h3>物流信息</h3> 
                <div class="col-md-6">
                  <div><label>收货人：</label><span><?=$model['full_name']?></span></div>
                  <div><label>联系方式：</label><span><?=$model['tel']?></span></div>
                  <div><label>收货地址：</label><span> <?=$model['province']['name']?>,<?=$model['city']['name']?>,<?=$model['region']['name']?>,<?=$model['address']?></span></div>
                  <div>   <label>配送方式：</label><span><?=Tools::getDelivery($model['delivery_id'])?></span></div>
                    <?php if($model['delivery_id']==2):?>
                  <div>   <label>自提点：</label><span><?=$model['orderPick']['pick']['name'].'   电话'.$model['orderPick']['pick']['tel']?></span></div>
                  <div>   <label>自提点地址：</label><span><?=$model['orderPick']['pick']['province']['name'].$model['orderPick']['pick']['city']['name'].$model['orderPick']['pick']['info']?></span></div>
                <?php endif;?>
                </div>
                 <?php if($model['delivery_status']==1):?>
                <div class="col-md-6">
                  <div><label>快递公司：</label><span><?=$model['orderDeliveryDoc']['shipping_name']?></span></div>
                  <div><label>快递编号：</label><span><?=$model['orderDeliveryDoc']['delivery_code']?></span></div>
                  <div><label>发货时间：</label><span><?php echo date('Y-m-d H:i:s',$model['orderDeliveryDoc']['addtime']);?></span></div>
                  <div><label>发货人：</label><span><?php echo Tools::get_user_name($model['orderDeliveryDoc']['admin_user']);?></span></div> 
                </div>
                   <?php endif;?>
            </div>
            <div class="list-item row">
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
                <div class="total">总计：￥<?=$model['sku_price_real']?></div>
            </div>
            <div class="list-item row">
                <div class="edit">          
                    <h3>费用信息</h3>
                   <?php if($model['payment_status']==0):?>
                    <a class="change" href="<?php echo Url::toRoute(['order/change-price','id'=>$model['id']]);?>"><i class="fa fa-edit"></i>修改费用</a>
                    <?php endif;?>
                </div>
                <div class="col-md-3">
                  <div><label>小计：</label><span><?=$model['sku_price_real']?></span></div>
                  <div><label>平台服务费：</label><span><?=$model['sku_price_real']*$percent?></span></div>
                 
                </div>
                <div class="col-md-3">
                  <div><label>运费：</label><span>+<?=$model['delivery_price_real']?></span></div>
                  <div><label>优惠券抵扣：</label><span>-<?=$model['coupons_price']?></span></div>
                </div>
                <div class="col-md-6">
                  <div><label>积分 (-<?=$model['integral']?>)：</label><span>-<?=$model['integral_money']?></span></div>
                  <div><label>价格调整：</label><span><?php echo $model['discount_price']>0?'减:'.$model['discount_price']:'加：'.abs($model['discount_price'])?></span></div>
                  <div><label>应付 ：</label><span style="color: #ff4444"><?=$model['pay_amount']?></span></div>
                </div>
            </div>
            <div class="list-item row">
                <h3>操作信息</h3> 
                <form class="form-horizontal">

                 <?php if(!in_array($model['status'],[5,10])):?>
            <!--         <div class="form-group">
                        <label class="col-sm-2">操作备注</label>
                        <div class="col-sm-10"><textarea class="form-control" rows="3" id="mark"></textarea></div>
                    </div> -->
                    <?php endif;?>
                    <div class="form-group">
                      <!--   <label class="col-sm-2">可执行操作 </label> -->
                        <div class="btn-box col-sm-10">

                           <?php if($model['payment_status']==0&&$model['status']==1):?>
                           <?php echo  Html::a('设为已付款', ['set-pay', 'id' => $model['id']], [ 'class' => 'btn btn-primary btn-flat btn-ajax', 'data-confirm' => '确认设为已付款吗?' ])?>
                           <?php elseif($model['payment_status']==1&&$model['delivery_status']==0&&$model['status']!=6&&$model['status']!=10):?>
                              <?php echo  Html::a('作废订单', ['one-cancel', 'id' => $model['id']], [ 'class' => 'btn btn-flat  btn-danger', 'data-confirm' => '确认作废订单吗?' ])?>
                            <a class="btn btn-primary btn-flat" href="<?=Url::to(['order/delivery','id'=>$model['id']])?>" class="fahgituo">去发货</a>

                           <?php elseif($model['payment_status']==1&&$model['delivery_status']==1&&!in_array($model['status'],[4,5,6,10])):?>
                              <?php echo  Html::a('设为已收货', ['receive', 'id' => $model['id']], [ 'class' => 'btn btn-primary btn-flat btn-ajax ', 'data-confirm' => '确认操作吗?' ])?>
                          <!--<p class="btn btn-primary btn-flat btn-ajax" data-status="0" id="error">无效</p> -->
                           <?php elseif($model['status']==4):?>
                                <p class=" btn btn-danger" data-status="re" >订单已收货，等待用户评价</p>
                            <?php endif;?>

                            <?php if($model['payment_status']==1&&in_array($model['status'],[2,3])):?> 
                                <?php echo  Html::a('设为已完成', ['finish', 'id' => $model['id']], [ 'class' => 'btn btn-primary btn-flat btn-ajax', 'data-confirm' => '确认操作吗?' ])?>
                                  <?php endif;?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="list-item row">

              <h3>操作记录</h3>
               <table class="table table-bordered table-hover table-responsive">
                   <thead>
                       <tr>
                           <th>操作者</th>
                           <th>操作时间</th>
                      <!--      <th>订单状态</th>
                           <th>付款状态</th>
                           <th>发货状态</th> -->
                           <th>描述</th>
                           <th>备注</th>
                       </tr>
                       </thead>
                       <tbody class="list">
            
                         <?php
                            echo ListView::widget([
                            'dataProvider' => $dataProvider,
                            'itemView' => 'log',
                            ]);
                            ?>
                       </tbody>
               </table>
              
            </div>
        </div>

    </div>
</div>



