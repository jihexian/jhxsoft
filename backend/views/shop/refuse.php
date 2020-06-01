<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Shop */
$this->title = '驳回申请';
$this->params['breadcrumbs'][] = ['label' => 'Shops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-create">

 <div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'readonly'=>true]) ?>
    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>
    <?= Html::activeHiddenInput($model,'apply_status',array('value'=>2)) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '保存' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>

</div>
