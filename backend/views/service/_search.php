<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceSerach */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box box-primary">
    <div class="box-header">
        <h2 class="box-title">service搜索</h2>
        <div class="box-tools"><button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" data-original-title="" title=""><i class="fa fa-minus"></i></button></div>
    </div>
    <div class="box-body">

        <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        ]); ?>

            <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'sku_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'company') ?>

    <?php // echo $form->field($model, 'delivery_no') ?>

    <?php // echo $form->field($model, 'mark') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'apply_status') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'receive_status') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'refund_type') ?>

    <?php // echo $form->field($model, 'mid') ?>

    <?php // echo $form->field($model, 'shop_id') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'mobile') ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
