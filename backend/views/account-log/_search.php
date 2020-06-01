<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AccountLogSearch */
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

    <?= $form->field($model, 'member_id') ?>    
    <?= $form->field($model, 'change_type')->dropDownList([1=>'积分',2=>'余额'], ['prompt' => '全部','options'=>[$model->change_type=>['Selected'=>true]]]) ?>
    
    
    <?php echo $form->field($model, 'type')->dropDownList($model->getTypeList(), ['prompt' => '全部','options'=>[$model->type=>['Selected'=>true]]]) ?>
    <?= Html::submitButton(Html::icon('search'), ['class' => 'btn btn-primary btn-flat']) ?>
    <div class="error-summary hide"><ul></ul></div>

    <?php ActiveForm::end(); ?>
</div>

