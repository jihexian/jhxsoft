<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RechargeSearch */
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

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_no') ?>

    <?= $form->field($model, 'm_id') ?>

    <?php  //echo  $form->field($model, 'pay_amount') ?>


    <?php  echo $form->field($model, 'payment_name') ?>

    <?=  $form->field($model, 'created_at')->widget(\yii\jui\DatePicker::classname(), [
     //'language' => 'ru',
      //'dateFormat' => 'yyyy-MM-dd',
  ]) ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'pay_status') ?>
    <?php echo $form->field($model, 'pay_status')->dropDownList(['0'=>'待支付','1'=>'已支付','2'=>'订单关闭'],['prompt'=>'请选择','style'=>'width:120px'])?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', 'Search'), ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::resetButton(Yii::t('common', 'Reset'), ['class' => 'btn btn-default btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
