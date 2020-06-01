<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Tree;
use common\models\RegionLocal;

/* @var $this yii\web\View */
/* @var $model common\models\RegionLocal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList(RegionLocal::getDropDownList(Tree::build(RegionLocal::lists(),'id','parent_id')), ['prompt' => '请选择','options' => [$model['id'] => ['disabled' => true]]]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
