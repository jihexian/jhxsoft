<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ShopRechargeSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box box-primary">
    <div class="box-header">
        <h2 class="box-title">shop-recharge搜索</h2>
        <div class="box-tools"><button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" data-original-title="" title=""><i class="fa fa-minus"></i></button></div>
    </div>
    <div class="box-body">

        <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        ]); ?>

            <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_no') ?>

    <?= $form->field($model, 'shop_id') ?>

    <?= $form->field($model, 'pay_amount') ?>

    <?= $form->field($model, 'score') ?>

    <?php // echo $form->field($model, 'payment_code') ?>

    <?php // echo $form->field($model, 'payment_name') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'pay_status') ?>

    <?php // echo $form->field($model, 'transaction_id') ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', 'Search'), ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::resetButton(Yii::t('common', 'Reset'), ['class' => 'btn btn-default btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
