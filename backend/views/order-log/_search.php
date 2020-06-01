<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="">
 <!--  <div class="box-header">
        <h2 class="box-title">搜索</h2>
        <div class="box-tools"><button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" data-original-title="" title=""><i class="fa fa-minus"></i></button></div>
    </div>  -->
    <div class="box-body">

        <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
        ]); ?>

    <?= $form->field($model, 'order_no') ?>
     <?= $form->field($model, 'action_user')->dropDownList($user, ['prompt' => '请选择操作人']) ?>
    
    <?= $form->field($model, 'order_status')->dropDownList($model->getStatusList(), ['prompt' => '全部','options'=>[$model->order_status=>['Selected'=>true]]]) ?>

    <?php // echo $form->field($model, 'pay_status') ?>

    <?php // echo $form->field($model, 'action_note') ?>

    	<div class="form-group">
	<label class="control-label" for="ordersearch-create_time">时间</label>
	 <?php
    echo DateRangePicker::widget([
        'model'=>$model,
        'attribute'=>'create_time',
        'convertFormat'=>true,
        'pluginOptions'=>[
            'timePicker'=>true,
            'timePickerIncrement'=>30,
            'locale'=>[
                'format'=>'Y-m-d'
            ]
        ]
    ]);
    ?>	
	    <div class="help-block"></div>
	    </div>

    <?php // echo $form->field($model, 'status_desc') ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', '搜索'), ['class' => 'btn btn-primary btn-flat']) ?>
            <?php //echo Html::resetButton(Yii::t('common', 'Reset'), ['class' => 'btn btn-default btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
