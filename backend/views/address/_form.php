<?php

use common\logic\RegionLogic;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Address */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'postalCode')->textInput(['maxlength' => true]) ?>



  <?= $form->field($model,'province_id')->dropDownList(RegionLogic::getRegions(null,1),
    [
        'prompt'=>'--请选择省--',
        'onchange'=>'
        
            $.post("'.yii::$app->urlManager->createUrl('pick/region').'?level=2&parent_id="+$(this).val(),function(data){
                $("select#pick-city_id").html(data);
            });',
    ]) ?>
    <?= $form->field($model,'city_id')->dropDownList(RegionLogic::getRegions(null,$model->province_id),
    [
        'prompt'=>'--请选择市--',
        'onchange'=>'
            $.post("'.yii::$app->urlManager->createUrl('pick/region').'?level=3&parent_id="+$(this).val(),function(data){
                $("select#pick-area_id").html(data);
            });',
    ]) ?>

	<?= $form->field($model, 'region_id')->dropDownList(RegionLogic::getRegions(null,$model->city_id),
    [
        'prompt'=>'--请选择地区--',
    ]) ?>

    <?= $form->field($model, 'nationalCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telNumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([1=>'启用',0=>'禁用']) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'is_default')->dropDownList([1=>'是',0=>'否']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
