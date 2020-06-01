<?php

use backend\models\Product;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '状态编辑: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '全平台商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = '修改';

/* @var $this yii\web\View */
/* @var $model common\models\Address */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <label class="col-xs-2">商品状态：</label>
        <div class="col-xs-10">
            <div>
            	<?= $form->field($model, 'status', ['template' => '{input}','options' => ['tag'=>false]])->dropDownList(Product::getStatusList(), ['class'=>'form-control width200 modelselect']) ?>  
            </div>                    
            <small style=""></small>
        </div>     
    </div>
    
    <div class="form-group save-box">
		<?= Html::submitButton($model->isNewRecord ? '保存' : '更新', ['class' => $model->isNewRecord ? 'btn btn-flat' : 'btn btn-primary btn-flat']) ?>
	</div>

    <?php ActiveForm::end(); ?>
    </div>
</div>