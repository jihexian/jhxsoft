<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年11月30日 下午12:07:57
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use  yii\helpers\Html;

?>
<?php if(empty(Yii::$app->user->identity->pay_pwd)):?>
<div class="weui-msg">
  <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg"></i></div>
  <div class="weui-msg__text-area">
    <h2 class="weui-msg__title">请先设置支付密码</h2>
  </div>
  <div class="weui-msg__opr-area">
    <p class="weui-btn-area">
      <a href="<?= Url::to(['/member/reset-pay-password'])?>" class="weui-btn weui-btn_primary">去设置</a>
      <a href="javascript:history.go(-1);" class="weui-btn weui-btn_default">返回</a>
    </p>
  </div>
<?php else:?>
	<header class="top-fixed">
		<div class="weui-flex top-box">
			<div onclick="javascript:history.back(-1);">
				<i class="iconfont icon-fanhui"></i>
			</div>
			<div class="weui-flex__item mgr9">余额支付</div>
			<!-- <div>
				<i class="iconfont " id="mulu-bt"></i>
			</div> -->
		</div>
	</header>

<div class="checked-pwd mgt68" style="margin-top: .8rem;">
<?php 
$form = ActiveForm::begin([
    'id' => 'form',
     'method'=>'post'
   
])?>

<div class="weui-cells weui-cells_form">
<div class="weui-cell">
<div class="weui-cell__hd"><label class="weui-label">订单号：</label></div>
<div class="weui-cell__bd">
<p><?=$order['order_no']?></p>
</div>
</div>
<div class="weui-cell">
<div class="weui-cell__hd"><label class="weui-label">支付金额：</label></div>
<div class="weui-cell__bd">
<p>￥<?=$order['pay_amount']?>元</p>
</div>
</div>
<div class="weui-cell">
<div class="weui-cell__hd"><label class="weui-label">账号余额：</label></div>
<div class="weui-cell__bd">
<p>￥<?=$member['user_money']==''?'0.00':$member['user_money']?>元</p>
</div>
</div>
<div class="weui-cell">
<div class="weui-cell__hd">
<label class="weui-label">支付密码：</label>
</div>
<div class="weui-cell__bd">
<?= $form->field($member, 'pay_pwd',['template' => '{input}{error}','options' => ['tag=>false']])
->passwordInput(['class'=>'weui-input','value' =>'']) ?>
</div>
<div class="weui-cell__ft">
<a href="<?=Url::to(['/member/reset-pay-password'])?>" class="weui-vcode-btn">忘记密码？</a>
</div>
</div>
</div>

</div>
<div class="weui-btn-area">
<input type="hidden" name="payment_code" value="<?=yii::$app->request->get('payment_code')?>"/>
<?= Html::submitButton('立即支付', ['class' => 'weui-btn weui-btn_primary fs34']) ?>

</div>
<?php ActiveForm::end() ?>
<?php endif; ?>
 