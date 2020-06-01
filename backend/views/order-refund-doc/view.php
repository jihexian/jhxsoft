<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年12月14日 上午9:35:42
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Html;
use common\helpers\Tools;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use yii\widgets\ListView;
$this->title =Yii::t('backend', 'Refund');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=Html::cssFile('@web/css/order.css')?>


<div class="box box-primary">
    <div class="box-body">
    <?php 
    $form = ActiveForm::begin([
        'options' => ['class' => 'form-inline'],
        'action' => ['save','id' => $model->id],
         
    ]);
    ?>
        
        <div class="order-container">
               <div class="list-item row">
                <h3>基本信息</h3> 
                <div class="col-md-3">
                  <div><label>订单ID：</label><span><?=$model['order']['id']?></span></div>
                  <div><label>订单号：</label><span><?=$model['order']['order_no']?></span></div>
                  <div><label>会员：</label><span><?php echo Tools::get_user_name($model['order']['m_id']); ?></span></div>
                <!--  <div><label>E-Mail:</label><span></span></div>  -->

                </div>
                <div class="col-md-3">
               <!--   <div><label>电话：</label><span>18277779458</span></div>-->
                  <div><label>应付金额：</label><span><?=$model['order']['pay_amount']?></span></div>
                  <div><label>订单状态：</label><span><?= Tools::get_status($model['order']['status'])?>/ <?= Tools::pay_status($model['order']['payment_status'])?> / <?=  Tools::shipping_status($model['order']['delivery_status'])?></span></div> 
                  <div><label>下单时间：</label><span><?php echo date('Y-m-d H:i:s',$model['order']['create_time'])?></span></div>
                </div>
                <div class="col-md-6">
                    <?php if (!empty($model['order']['payment_status'])): ?>
                  <div><label>支付时间：</label><span><?php echo date('Y-m-d H:i:s',$model['order']['paytime'])?></span></div>
                  <div><label>支付方式：</label><span><?=$model['order']['payment_name']?></span></div>
                   <?php endif; ?>

                  <div><label>用户备注：</label><span><?=$model['order']['m_desc']?></span></div>
                </div>
            </div>
            <div class="list-item row">
                <h3>收货信息</h3> 
                <div class="col-md-12">
                  <div><label>收货人：</label><span><?=$model['order']['full_name']?></span></div>
                  <div><label>联系方式：</label><span><?=$model['order']['tel']?></span></div>
                  <div><label>收货地址：</label><span> <?=$model['order']['prov']?>,<?=$model['order']['city']?>,<?=$model['order']['area']?>,<?=$model['order']['address']?></span></div>
                <div><label>退款理由：</label><span><?php echo $model['note']?></span></div> 
                <!-- <div><label>配送方式：</label><span></span></div>-->
                </div>
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
                               
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                </table>
             <div class="total">总计：￥<?=$model['order']['pay_amount']?></div>
            </div>
           <?php if(($model['status']==0)):?>
            <div class="list-item row">
                <h3>操作信息</h3> 
                    
                     <div class="form-group">
                 <!--   <label class="col-sm-2">审核意见</label> -->
                        <div class="col-sm-10">
                       <?php echo $form->field($model, 'check_status')->radioList(['1'=>'同意退款','0'=>'拒绝退款']) ?>
                        </div>
                    </div>
                    <div class="form-group">
               
                        <div class="col-sm-10">
                       <?php echo $form->field($model, 'type')->radioList(['0'=>'原路退返','1'=>'退回账号余额']) ?>
                        </div>
                    </div>
                       <div class="form-group">
               
                        <div class="col-sm-10">
                         <?= $form->field($model, 'amount')->input('text',['value'=>$order['pay_amount']]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2">操作备注</label>
                        <div class="col-sm-10"><?= $form->field($model,"message",[
                            'options'   => [],
                            'template'  => "{input}{error}"
                        ])->textarea([
                            'autofocus'     => false,
                            'placeholder'   => '',
                            'style'=>'width:600px;height:100px;',
                            
                        ])
                       ?></div>
                        
                    </div>
    
                    <div class="form-group">
                 
                        <div class="btn-box col-sm-10">
                    
                        
                     
                       <?= Html::submitButton('确认退款',["class" => "btn btn-primary btn-flat btn-xs", "type" => "button"]) ?>
                
                        </div>
                    </div>
             
            </div>
               <?php else:?>
                  
                <div class="list-item row">
                <h3>退款信息</h3> 
                <div class="col-md-6">
                  <div><label>审核状态：</label><span><?php echo $model['check_status']==0?'拒绝':'通过'?></span></div>
                <div><label>操作员：</label><span><?php echo Tools::get_admin_name($model['admin_user'])?></span></div> 
                </div>
                  <div class="col-md-6">
                <div><label>退款形式：</label><span><?php echo $model['type']==0?'原路退返':'退回账号余额'?></span></div> 
                <div><label>实际退款金额：</label><span><?php echo $model['amount']?></span></div> 
                </div>
                  <div class="col-md-6">
                <div><label>操作备注：</label><span><?php echo $model['message']?></span></div> 
            
                </div>
            </div>
            <?php endif;?> 
            <?php ActiveForm::end(); ?> 
    

</div>
    </div>

   <?php

$this->registerJs(<<<JS
$(function(){
    $('.btn-xs').click(function(e){
 var CheckBox = document.getElementsByName('checkBox[]');//获取所有的checkBox
        var count=0;
        for(i=0;i < CheckBox.length;i++){
            if(CheckBox[i].checked == true){                                
                count++;
            }
        }
        if(count == 0 ){
            var errorMeg = document.getElementById('HomeworkTrConfig_flag_em_');
            errorMeg.style.display = "";
            errorMeg.innerHTML="请至少选择一项";           
            return false;
        }else{
            return true;
        }

   });
});
JS
);
?>

