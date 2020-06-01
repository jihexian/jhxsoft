<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductThemeSearch */
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

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'status')->dropDownList([0=>'禁用',1=>'启用',2=>'删除'], ['prompt' => '全部','options'=>[$model->status=>['Selected'=>true]]]) ?>
    <?= Html::submitButton(Html::icon('search'), ['class' => 'btn btn-primary btn-flat']) ?>
    <div class="error-summary hide"><ul></ul></div>

    <?php ActiveForm::end(); ?>

</div>

