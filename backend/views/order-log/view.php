<?php
/**
 * 退款操作
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年12月21日 上午10:06:04
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use yii\widgets\ListView;
$this->title =Yii::t('backend', 'Relivery');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=Html::cssFile('@web/css/order.css')?>


<div class="box box-primary">
    <div class="box-body">
    <?php 
    $form = ActiveForm::begin([
        'options' => ['class' => 'form-inline'],
    ]);
    ?>
        <div class="print"><a class="btn btn-primary btn-flat btn-xs" href="<?php echo Url::toRoute(['order/print','id'=>1]);?>"><i class="fa fa-print"></i>打印订单</a></div>
        <div class="order-container">
            <div class="list-item row">
                <h3>基本信息</h3> 
                <div class="col-md-3">
                  <div><label>订单号：</label><span><?=$model['order_no']?></span></div>
                  <div><label>配送费用：</label><span><?=$model['delivery_price_real']?></span></div> 
                </div>
                <div class="col-md-3">
                  <div><label>下单时间：</label><span><?php echo date('Y-m-d H:i:s',$model['create_time'])?></span></div>
                  <div> <?=$form->field($delivery, 'express_company_id')->dropdownList($items,
    ['prompt'=>'请选择快递']
);?></div>
                </div>
                <div class="col-md-6">            
                  <div><label>配送方式：</label><span>普通</span></div>
                  <div><span><?= $form->field($delivery, 'delivery_code') ?></span></div>
                </div>
            </div>
            <div class="list-item row">
                <h3>收货信息</h3> 
                <div class="col-md-12">
                  <div><label>收货人：</label><span><?=$model['full_name']?></span></div>
                  <div><label>联系方式：</label><span><?=$model['tel']?></span></div>
                  <div><label>收货地址：</label><span> <?=$model['prov']?>,<?=$model['city']?>,<?=$model['area']?>,<?=$model['address']?></span></div>
               
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
                            <th>选择发货</th>                                        
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
                                <?php if($vo['is_send']==0):?>
                                <th><input type="checkbox" name="sku[]" value="<?=$vo['id']?>" checked="checked"></th>
                                <?php else:?>
                                 <th>已经发货</th>
                                 <?php endif;?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                </table>
            
            </div>
           <?php if(($model['status']==3||$model['status']==2)&&($model['delivery_status']==0||$model['delivery_status']==2)):?>
            <div class="list-item row">
                <h3>操作信息</h3> 
               
                    <div class="form-group">
                        <label class="col-sm-2">操作备注</label>
                        <div class="col-sm-10"><?= $form->field($delivery,"note",[
        'options'   => [],
        'template'  => "{input}{error}"
    ])->textarea([
        'autofocus'     => false,
        'placeholder'   => '',
        'style'=>'width:600px;height:100px;',
        
    ])
   ?></div>
                        



                    </div>
                    
                </div>
                 <div class="list-item row">
                    <div class="form-group">
                      <label class="col-sm-2">可执行操作 </label>
                        <div class="btn-box col-sm-10">
                    
                        
                     
                       <?= Html::submitButton('确认发货',["class" => "btn btn-primary btn-flat btn-xs", "type" => "button"]) ?>
                
                        </div>
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
        console.log(count);
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

