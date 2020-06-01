<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ShopCategory;

/* @var $this yii\web\View */
/* @var $model common\models\ShopSearch */
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
    <?= $form->field($model, 'status')->dropDownList(['1'=>'启用','0'=>'禁用'], ['prompt' => '全部']) ?>
   <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(ShopCategory::find()->where(['status'=>1])->all(), 'id', 'name')) ?>
    <?= $form->field($model, 'apply_status')->dropDownList(['0'=>'待审核','1'=>'已通过','2'=>'驳回重改'], ['prompt' => '全部']) ?>
    <?= Html::submitButton(Html::icon('search'), ['class' => 'btn btn-primary btn-flat']) ?>
    <div class="error-summary hide"><ul></ul></div>

    <?php ActiveForm::end(); ?>

</div>

