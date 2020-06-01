<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
/* @var $this yii\web\View */
/* @var $model common\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-header">
        <h2 class="box-title">订单搜索</h2>
        <div class="box-tools"><button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" data-original-title="" title=""><i class="fa fa-minus"></i></button></div>
    </div>
    <div class="box-body">

        <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
        ]);
       //$colClass = ['class' => 'col-sm-3 control-label no-padding-right'];
        ?>




    <?= $form->field($model, 'order_no')->label() ?>
    <?php  echo $form->field($model, 'payment_no') ?>

    <?php  //echo $form->field($model, 'delivery_id') ?>

    <?php // echo $form->field($model, 'delivery_time') ?>

    <?php // echo $form->field($model, 'delivery_status') ?>

    <?php // echo $form->field($model, 'shop_id') ?>

    <?php // echo $form->field($model, 'is_shop_checkout') ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusList(), ['prompt' => '全部','options'=>[$model->status=>['Selected'=>true]]]) ?>

    <?php  echo $form->field($model, 'full_name')->textInput(['autocomplete' => 'off']) ?>

    <?php  //echo $form->field($model, 'tel') ?>

    <?php  //echo $form->field($model, 'prov') ?>

    <?php //  echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'sku_price') ?>

    <?php // echo $form->field($model, 'sku_price_real') ?>

    <?php // echo $form->field($model, 'delivery_price') ?>

    <?php // echo $form->field($model, 'delivery_price_real') ?>

    <?php // echo $form->field($model, 'discount_price') ?>

    <?php // echo $form->field($model, 'promotion_price') ?>

    <?php // echo $form->field($model, 'coupons_price') ?>

    <?php // echo $form->field($model, 'order_price') ?>

    <?php // echo $form->field($model, 'coupons_id') ?>

    <?php // echo $form->field($model, 'm_desc') ?>

    <?php // echo $form->field($model, 'admin_desc') ?>

    <?php // echo $form->field($model, 'create_time') ?>
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

        <?php // echo $form->field($model, 'sendtime') ?>

    <?php // echo $form->field($model, 'completetime') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <?php // echo $form->field($model, 'update_time') ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', '搜索'), ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::resetButton(Yii::t('common', '重填'), ['class' => 'btn btn-default btn-flat']) ?>
            <a class="btn btn-default export" href="javascript:;">导出数据</a>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
