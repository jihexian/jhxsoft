<?php

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
    body{background-color: #fff;}
    .form-group{width: 100%;}
    .sitename{font-size: .3rem;text-align: center;color: #09bb07;}
</style>
<div class="wrap login">
<?php $form = ActiveForm::begin(['id' => 'login-form','enableAjaxValidation' => true,'enableClientValidation' => true]); ?>
        <div class="login-logo">
            <?php $logo = Yii::$app->config->get('site_logo'); ?>
            <?php if(empty($logo)): ?>
               <div class="sitename"> <?= $this->title ? Html::encode($this->title) . '-' . Yii::$app->config->get('site_name') : Yii::$app->config->get('site_name') ?></div>
                <?php else:?>
                <img src="<?= Yii::$app->config->get('site_logo') ?>" alt="">
            <?php endif;?>
        </div>
    	<div class="login-box">
            <div class="input-box">
                <div><i class="iconfont icon icon-shoujihao"></i></div>
                <?= $form
                ->field($model, 'mobile', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => '请输入手机号','type'=>'number']) ?>
            </div>
            <div class="input-box">
                <div><i class="iconfont icon icon-mima"></i></div>
            	<?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' => '请输入密码']) ?>
            </div>
                <?= Html::submitButton('登录', ['class'=>'login-btn confirm-btn','style'=>'border:none','name' =>'submit-button']) ?>
            

            <div class="box-bt" style="margin-bottom: 25px;"><a href="<?= \yii\helpers\Url::to(['site/register'])?>" class="toregister">还没账号？去注册~</a><a href="<?= \yii\helpers\Url::to(['member/reset-password'])?>" class="forget-password">忘记密码</a></div>
            
    	</div>
    	<?php 
ActiveForm::end(); 
?>
  	</div>
<div  style="height: 150px;width: 100%;margin-top: 20px;text-align: center;color: #999;"></div>


        
