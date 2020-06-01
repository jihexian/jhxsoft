<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;
use common\helpers\Util;
use yii\helpers\Json;
use common\assets\LayerAsset;
LayerAsset::register($this);
/* @var $this yii\web\View */
/* @var $model common\modules\coupon\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
$this->title = '上传优惠券';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' ?>
<?php $this->endBlock() ?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <input type="hidden" name="card_id" value="<?= Yii::$app->request->get('id')?>">
    <?= $form->field($model, 'file')->fileInput() ?>

	<?=  Html::submitButton('提交', ['class'=>'btn btn-primary','name' =>'submit-button']) ?>
    <?php ActiveForm::end() ?>
    </div>
</div>
<?php $this->beginBlock('spec') ?>  
    
<?php $this->endBlock() ?>  
<?php $this->registerJs($this->blocks['spec'], \yii\web\View::POS_END); ?>