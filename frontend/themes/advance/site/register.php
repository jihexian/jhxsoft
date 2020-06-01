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
<style type="text/css">
/*  body .weui-tabbar{position: static !important;}*/
  html,body{overflow: scroll;}
  body{background-color: #fff;}

 .form-group{width: 100%;}
</style>
<div class="wrap register" style="margin-top: 8vh;">
<?php $form = ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true,'enableClientValidation'=>false,'validateOnChange'=>false,'validateOnBlur'=>false]); ?>
    	<div class="register-box">
            <div class="input-box">
                <div><i class="iconfont icon icon-shoujihao"></i></div>
    	    	<?= $form
                ->field($model, 'mobile', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => '请输入手机号','type'=>'number']) ?>
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
         
            <?= Html::submitButton('注册', ['class'=>'register-btn confirm-btn','style'=>'border:none','name' =>'submit-button']) ?>
      	
            <div class="box-bt"><a href=<?= \yii\helpers\Url::to(['site/login'])?>>已有账号？去登录~</a></div>
            
    	</div>
    	    	<?php 
ActiveForm::end(); 
?>
  	</div>
    <div  style="height: 150px;width: 100%;margin-top: 20px;text-align: center;color: #999;"></div>
  	<?php
$this->registerJs(<<<JS
    let Height = $('body').height();
    $('.register-box input').focus(function(){
        $(window).resize(function() {
            $('.register-box').height(Height);
        });
    })
JS
);
?>
        
