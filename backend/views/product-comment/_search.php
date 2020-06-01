<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductCommentSearch */
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
	<?= $form->field($model, 'comment_id') ?>
	<?php  echo $form->field($model, 'order_no') ?>
    <?= $form->field($model, 'member_id') ?>
    <?php  echo $form->field($model, 'order_sku_id') ?>
    
    <?= $form->field($model, 'content') ?> 
    <?= $form->field($model, 'reply_status')->dropDownList(['1'=>'已回复','0'=>'未回复
			
        '], ['prompt' => '全部','options'=>[$model->status=>['Selected'=>true]]]) ?>   
	<?= $form->field($model, 'status')->dropDownList(['1'=>'显示','0'=>'隐藏
			
        '], ['prompt' => '全部','options'=>[$model->status=>['Selected'=>true]]]) ?>
    <?= $form->field($model, 'appraise')->dropDownList(['1'=>'差评','2'=>'中评','3'=>'好评'], 
    		['prompt' => '全部','options'=>[$model->appraise=>['Selected'=>true]]]) ?>
    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'image') ?>

    
   

    <?php // echo $form->field($model, 'total_stars') ?>

    <?php // echo $form->field($model, 'des_stars') ?>

    <?php // echo $form->field($model, 'delivery_stars') ?>

    <?php // echo $form->field($model, 'service_stars') ?>

        <div class="form-group">
            <?= Html::submitButton(Html::icon('search'), ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::resetButton('重置', ['class' => 'btn btn-default btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>	

</div>
