<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Withdrawal;
use common\models\Plugin;

/* @var $this yii\web\View */
/* @var $model common\models\WithdrawalSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box box-primary">
    <div class="box-header">
        <h2 class="box-title">搜索</h2>
        <div class="box-tools"><button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" data-original-title="" title=""><i class="fa fa-minus"></i></button></div>
    </div>
    <div class="box-body">

        <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
        ]); ?>

    <?php //echo  $form->field($model, 'id') ?>

    <?= $form->field($model, 'm_id') ?>

    <?php //echo $form->field($model, 'pay_amount') ?>


     <?php echo $form->field($model, 'created_at')->widget(\yii\jui\DatePicker::classname(), [
     //'language' => 'ru',
      //'dateFormat' => 'yyyy-MM-dd',
  ]) ?>

    <?php // echo $form->field($model, 'updated_at') ?>


    <?php //echo $form->field($model, 'bank_name')->dropDownList(ArrayHelper::map(Plugin::find()->where(['type'=>'payment'])->all(), 'name', 'name')) ?>
    <?= $form->field($model, 'bank_name')->dropDownList(
       Plugin::find()->where(['type'=>'payment'])->select(['name'])->indexBy('name')->column(), ['prompt' => '请选择', 'value' =>$model->bank_name]
) ?>


    <?php  echo $form->field($model, 'bank_card') ?>

    <?php // echo $form->field($model, 'realname') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'taxfee') ?>


    <?php echo $form->field($model, 'status')->dropDownList(['0'=>'申请中','1'=>'完成提现','2'=>'拒绝申请'],['prompt'=>'请选择','style'=>'width:120px'])?>

    <?php  echo $form->field($model, 'transaction_id') ?>

    <?php // echo $form->field($model, 'error_code') ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
