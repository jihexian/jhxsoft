<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Tree;
use common\models\ProductCategory;
use common\models\ProductType;
use common\models\CategoryModel;
use yii\helpers\ArrayHelper;
use common\models\Shop;
/* @var $this yii\web\View */
/* @var $model common\models\ProductSearch */
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

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'name') ?>
    
	<?= $form->field($model, 'type_id')->dropDownList(ProductType::getDropDownList(Tree::build(ProductType::lists(),'type_id','parent_id')), ['prompt' => '全部']) ?>
	<?= $form->field($model, 'shop_id')->dropDownList(ArrayHelper::map(Shop::find()->where(['status'=>1])->asArray()->all(), 'id', 'name'), ['prompt' => '全部']) ?>
    <?= $form->field($model, 'status')->dropDownList($model->getStatusList(), ['prompt' => '全部','options'=>[$model->status=>['Selected'=>true]]]) ?>
    <?= $form->field($model, 'is_index_show')->dropDownList([0=>'否',1=>'是'], ['prompt' => '全部','options'=>[$model->is_index_show=>['Selected'=>true]]]) ?>
    
    <?= Html::submitButton(Html::icon('search'), ['class' => 'btn btn-primary btn-flat']) ?>
    <div class="error-summary hide"><ul></ul></div>

    <?php ActiveForm::end(); ?>

</div>
