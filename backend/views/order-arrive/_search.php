<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderArriveSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box box-primary">
    <div class="box-header">
        <h2 class="box-title">order-arrive搜索</h2>
        <div class="box-tools"><button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" data-original-title="" title=""><i class="fa fa-minus"></i></button></div>
    </div>
    <div class="box-body">

        <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        ]); ?>

            <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_no') ?>

    <?= $form->field($model, 'pay_amount') ?>

    <?= $form->field($model, 'm_id') ?>

    <?= $form->field($model, 'payment_status') ?>

    <?php // echo $form->field($model, 'shop_id') ?>

    <?php // echo $form->field($model, 'is_shop_checkout') ?>

    <?php // echo $form->field($model, 'order_price') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'payment_no') ?>


    <?php // echo $form->field($model, 'payment_name') ?>

    <?php // echo $form->field($model, 'paytime') ?>

        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::resetButton('Reset', ['class' => 'btn btn-default btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
