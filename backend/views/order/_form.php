<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'm_id')->textInput() ?>

    <?= $form->field($model, 'order_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_code')->textInput() ?>

    <?= $form->field($model, 'payment_status')->textInput() ?>

    <?= $form->field($model, 'payment_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'delivery_id')->textInput() ?>

    <?= $form->field($model, 'delivery_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'delivery_status')->textInput() ?>

    <?= $form->field($model, 'shop_id')->textInput() ?>

    <?= $form->field($model, 'is_shop_checkout')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prov')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sku_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sku_price_real')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'delivery_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'delivery_price_real')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discount_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'promotion_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coupons_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coupons_id')->textInput() ?>

    <?= $form->field($model, 'm_desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'admin_desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'paytime')->textInput() ?>

    <?= $form->field($model, 'sendtime')->textInput() ?>

    <?= $form->field($model, 'completetime')->textInput() ?>

    <?= $form->field($model, 'is_del')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
