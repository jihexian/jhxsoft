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
    body{background: #fff;}
</style>
<div class="wrap register">
<?php $form = ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true,'enableClientValidation'=>false,'validateOnChange'=>false,'validateOnBlur'=>false]); ?>
    	<div class="register-box" style="margin-top: 10vh;">
            <div class="input-box">
                <div><i class="iconfont icon icon-shoujihao"></i></div>
    	    	<?= $form
                ->field($model, 'mobile', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => '请输入手机号']) ?>
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
            <?= Html::submitButton('确认绑定', ['class'=>'register-btn confirm-btn','style'=>'border:none','name' =>'submit-button']) ?>
         	
            
    	</div>
    	    	<?php 
ActiveForm::end(); 
?>
  	</div>
  	
        
