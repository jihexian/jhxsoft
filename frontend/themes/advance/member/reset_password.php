<?php

use common\widgets\sms\SmsWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<?php if(!Yii::$app->user->isGuest&&empty(Yii::$app->user->identity->mobile)):?>
<style type="text/css">
    body{background:#fff; }
</style>

<?php if(!yii::$app->user->isGuest&&empty(Yii::$app->user->identity->mobile)):?>
<div class="weui-msg">
  <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg"></i></div>
  <div class="weui-msg__text-area">
    <h2 class="weui-msg__title">请先绑定手机号</h2>
  </div>
  <div class="weui-msg__opr-area">
    <p class="weui-btn-area">
      <a href="<?= Url::to(['/member/bind-mobile'])?>" class="weui-btn weui-btn_primary">绑定手机</a>
      <a href="javascript:history.go(-1);" class="weui-btn weui-btn_default">返回</a>
    </p>
  </div>
<?php else:?>
<div class="wrap register">
<?php $form = ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true,'enableClientValidation'=>false,'validateOnChange'=>false,'validateOnBlur'=>false]); ?>
    	<div class="register-box" style="margin-top: 10vh;">
        <div class="input-box">
          <div><i class="iconfont icon icon-shoujihao"></i></div>
    
	    	    <?php
	    	    if(!yii::$app->user->isGuest&&!empty(Yii::$app->user->identity->mobile)){
	    	    echo $form
            ->field($model, 'mobile', $fieldOptions1)
            ->label(false)
            ->textInput(['value' => Yii::$app->user->identity->mobile,'readonly'=>'readonly']);
	    	    }else{
	    	       echo $form
	    	        ->field($model, 'mobile', $fieldOptions1)
	    	        ->label(false)
	    	        ->textInput();
	    	    }?>
        </div>
        <div class="input-box">
            <?= SmsWidget::widget(['scene'=>1,'form'=>$form,'model'=>$model,'attr'=>'verifyCode']);?> 
        </div>
        <div class="input-box">
          <div><i class="iconfont icon icon-mima"></i></div>   
	    	  <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => '请输入密码']) ?>
        </div>
        <div class="input-box">
          <div><i class="iconfont icon icon-mima"></i></div>
			    <?= $form
            ->field($model, 'verifyPassword', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => '请输入确认密码']) ?>
        </div> 
        <?= Html::submitButton('确认重置', ['class'=>'register-btn confirm-btn','style'=>'border:none','name' =>'submit-button']) ?>   
    	</div>
    	    	<?php 
ActiveForm::end(); 
?>
  	</div>
<?php endif; ?>
  	
        
