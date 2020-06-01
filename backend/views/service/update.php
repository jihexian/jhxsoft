<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\helpers\Tools;
use common\models\ShippingCompany;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
$this->title =Yii::t('common', 'Service Info');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=Html::cssFile('@web/css/order.css')?>


<div class="box box-primary">
    <div class="box-body">
     <?php 
    $form = ActiveForm::begin([
        'options' => ['class' => 'form-inline'],
        'action' => ['exchange','id' => $model->id],
         
    ]);
    ?>
        <div class="order-container">
            <div class="list-item row">
                <h3>订单信息</h3> 
                <div class="col-md-3">
                  <div><label>父单编号：</label><span><?=$model['orderSku']['order']['parent_sn']?></span></div>
                  <div><label>订单编号：</label><span><?=$model['orderSku']['order']['order_no']?></span></div>
                  <div><label>会员：</label><span><?php echo Tools::get_user_name($model['orderSku']['order']['m_id']); ?></span></div>
                <!--  <div><label>E-Mail:</label><span></span></div>  -->

                </div>
        
                <div class="col-md-3">
                  <div><label>支付时间：</label><span><?php echo date('Y-m-d H:i:s',$model['orderSku']['order']['paytime'])?></span></div>
                  <div><label>支付方式：</label><span><?php echo $model['orderSku']['order']['payment_name']?></span></div>
     
                </div>
                 <div class="col-md-3">
                  <div><label>收货人：</label><span><?php echo $model['orderSku']['order']['full_name']?></span></div>
                  <div><label>联系电话：</label><span><?php echo $model['orderSku']['order']['tel']?></span></div>
                  <div><label>收货地址：</label><span><?= $model['orderSku']['order']['prov']?>,<?= $model['orderSku']['order']['city']?>,<?= $model['orderSku']['order']['area']?>,<?= $model['orderSku']['order']['address']?></span></div>
                </div>
            </div>
         
            <div class="list-item row">
                <h3>售后商品</h3> 
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
           
                            <tr>                                     
                                <th><?=$model['orderSku']['goods_name']?></th>
                                <th><?=$model['orderSku']['sku_value']?></th>
                                <th><?=$model['orderSku']['num']?></th>
                                <th><?=$model['orderSku']['sku_market_price']?></th>
                                <th><?=$model['orderSku']['sku_sell_price_real']?></th>
                                <th><?php echo($model['orderSku']['sku_sell_price_real']*$model['orderSku']['num']);?></th>
                            </tr>
            
                        </tbody>
                </table>
                <div class="total">总计：￥<?php echo($model['orderSku']['sku_sell_price_real']*$model['orderSku']['num']);?></div>
            </div>
           <div class="list-item row">
                <h3>申请详情</h3> 
                <div class="col-md-3">
                  <div><label>申请类型：</label><span><?php echo $model->getType();?></span></div>
                  <div><label>申请理由：</label><span><?php echo $model['mark']?></span></div>
                </div>
        
                <div class="col-md-3">
                  <div><label>联系人：</label><span><?=$model['name']?></span></div>
               
                </div>
                 <div class="col-md-3">
                   <div><label>联系电话：</label><span><?=$model['mobile']?></span></div>
               
              
       
                </div>
            </div>
              <div class="list-item row">
                <h3>用户寄回商品物流</h3> 
         
                 <div class="col-md-3">
                  <div><label></label><span><?= $form->field($model, 'company')->dropDownList(
       ShippingCompany::find()->where(['status'=>1])->select(['company_name','code'])->indexBy('code')->column(), ['prompt' => '请选择', 'value' =>$model->company]
) ?></span></div>
                  <div><label></label><span> <?= $form->field($model, 'delivery_no')->input('text',['value'=>$model['delivery_no']]) ?></span></div>
                  <div><label></label><span> <?= $form->field($model, 'status')->input('text',['value'=>$model['delivery_no']]) ?></span></div>
                </div>
               
                  <div class="col-md-12">
                  <div class="form-group">
                        <div class="btn-box col-sm-10">    
                       <?= Html::submitButton('确认',["class" => "btn btn-primary btn-flat btn-xs", "type" => "button"]) ?>
                   
                        </div>
                    </div>
                  </div>
            </div>
        
        
        
              
        
          
                    
                  
    
                  
            
             
        
            <?php ActiveForm::end(); ?>  
        
        </div>

    </div>
</div>

