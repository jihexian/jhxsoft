<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = '改密: ' . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '改密';
?>


<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>  
	<?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('更新', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
