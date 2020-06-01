<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VillageCommissionLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="article-search">

    <?php
    Yii::$container->set(\yii\widgets\ActiveField::className(), ['template' => "{label}\n{input}"]);
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
        ]); ?>

            <?= $form->field($model, 'id') ?>


    <?= $form->field($model, 'order_no') ?>

    <?= $form->field($model, 'm_id') ?>

    <?= $form->field($model, 'shop_id') ?>

    <?php // echo $form->field($model, 'money') ?>

    <?php // echo $form->field($model, 'percentage') ?>

    <?php  echo $form->field($model, 'village_id') ?>

    <?php // echo $form->field($model, 'desc') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>


        <div class="form-group">
            <?= Html::submitButton(Html::icon('search'), ['class' => 'btn btn-primary btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>


</div>
