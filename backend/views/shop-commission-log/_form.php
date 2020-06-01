<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ShopCommissionLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'order_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'm_id')->textInput() ?>

    <?= $form->field($model, 'shop_id')->textInput() ?>

    <?= $form->field($model, 'money')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'percentage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>