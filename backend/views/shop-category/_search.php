<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ShopCategorySearch */
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

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'percent') ?>

    <?= $form->field($model, 'sort') ?>

    <?= Html::submitButton(Html::icon('search'), ['class' => 'btn btn-primary btn-flat']) ?>
    <div class="error-summary hide"><ul></ul></div>

        <?php ActiveForm::end(); ?>

</div>
