<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RegionLocalSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box box-primary">
    <div class="box-header">
        <h2 class="box-title">region-local搜索</h2>
        <div class="box-tools"><button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" data-original-title="" title=""><i class="fa fa-minus"></i></button></div>
    </div>
    <div class="box-body">

        <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        ]); ?>

            <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'parent_id') ?>

        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::resetButton('Reset', ['class' => 'btn btn-default btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
