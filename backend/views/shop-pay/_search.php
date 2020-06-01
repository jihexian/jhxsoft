<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ShopPaySearch */
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

 

    <?= $form->field($model, 'acount') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'bank') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?= Html::submitButton(Html::icon('search'), ['class' => 'btn btn-primary btn-flat']) ?>
    <div class="error-summary hide"><ul></ul></div>

        <?php ActiveForm::end(); ?>

   
</div>
