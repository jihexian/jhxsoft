<?php
/**
   * 选择支付方式
 * Author wsyone wsyone@faxmail.com 
 * Time:2019年5月17日下午5:10:20
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
       <form action="<?=url::to(['../payment/code'])?>" method="get">
  	    <div class="weui-cells weui-cells_radio">
  	           <?php foreach ($payment as $key=>$vo):?>
	             <label class="weui-cell weui-check__label" for="x<?=$key+1?>">
	                <div class="weui-cell__hd radio_checked">
	                    <input type="radio" class="weui-check" value="<?=$vo['id']?>" name="payment_code" id="x<?=$key+1?>" <?php if($key==0):?> checked="checked" <?php endif;?> >
	                    <i class="weui-icon-checked"></i>
	                </div>
	                <div class="weui-cell__bd">
	                    <i class="iconfont icon-yue"></i><p><?=$vo['name']?><?php if($vo['id']=='money'):?><span style='color:red;'>(<?=empty($user_money['user_money'])?'0.00':$user_money['user_money']?>)</span><?php endif;?></p>
	                </div>
	            </label>
	           <?php endforeach;?>    
	        </div>
	        
	      <div class="weui-btn-area">
	        <input type="hidden" name="order_id" value="<?= Yii::$app->request->get('order_id') ?>"/>
	        <input type="hidden" name="parent_sn" value="<?=$parent_sn?>"/>
	        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <button type="submit" class="weui-btn weui-btn_primary fs28">去支付 </button>
          </div>
          
	      </form>
	      
	      <div class="weui-cells weui-cells_radio">
	         

	        </div>