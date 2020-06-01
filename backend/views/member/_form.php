<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\MemberLevel;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\Member */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
    <?php if($model->isNewRecord){$model->sex = '男';$model->status = 1;}?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>    
    
    <?= $form->field($model, 'sex')->radioList(['男' => '男', '女' => '女'], ['style' => 'padding-top: 5px;'])->label('性别') ?>

    <?= $form->field($model, 'age')->textInput() ?>


    <?= $form->field($model, 'level')->dropDownList(ArrayHelper::map(MemberLevel::GetParentOrderBySort(),'id','name')) ?>

    <?=$form->field($model, 'type')->dropDownList([
                '1' => '个人',
                '2' => '机关单位',
                '3'=>'站长'
        ])?>
    <?= $form->field($model, 'status')->radioList(['1' => '启用', '0' => '禁用'], ['style' => 'padding-top: 5px;'])->label('状态') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
