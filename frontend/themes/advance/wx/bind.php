<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<div class="wrap register">
<?php $form = ActiveForm::begin(['action' => ['wx/bind-register'],'method'=>'post','id' => 'login-form', 'enableAjaxValidation' => true,'enableClientValidation'=>false,'validateOnChange'=>false,'validateOnBlur'=>false]); ?>
    	<div class="register-box">
	    	<?= $form
            ->field($model, 'mobile', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => '请输入手机号']) ?>           
           
	    	<?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => '请输入密码']) ?>
			
            <div class="register-btn  confirm-btn">
            <?= Html::submitButton('绑定', ['class'=>'register-btn confirm-btn','style'=>'border:none','name' =>'submit-button']) ?>
            </div>   	
            <div class="box-bt"><a href=<?= \yii\helpers\Url::to(['/wx/register'])?>>不绑定，跳过</a></div>
            
    	</div>
    	    	<?php 
ActiveForm::end(); 
?>
  	</div>
  	
        
