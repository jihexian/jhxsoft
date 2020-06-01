<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ShopPay;

/* @var $this yii\web\View */
/* @var $model common\models\ShopWithdraw */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'money',['inputOptions'=>['class'=>'form-control','placeholder'=>'可提现金额'.($shop->money?$shop->money:'0.00')]])->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'shop_id')->textInput() ?>

    <?php // $form->field($model, 'apply_id')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList(ShopPay::getList(Yii::$app->session->get('shop_id'))) ?>

    <?php // $form->field($model, 'account')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'status')->textInput() ?>

    <?php // $form->field($model, 'mark')->textInput(['maxlength' => true]) ?>
    <?php echo Html::activeHiddenInput($shop, 'version');?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提现' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
