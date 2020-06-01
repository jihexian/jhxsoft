<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Tree;
use common\models\ProductCategory;
use common\models\ProductType;
use common\models\CategoryModel;
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

    <?= $form->field($model, 'cat_id')->dropDownList(ProductCategory::getDropDownList(Tree::build(ProductCategory::lists(null,Yii::$app->session->get('shop_id')),'category_id','parent_id')), ['prompt' => '全部']) ?>
	<?= $form->field($model, 'type_id')->dropDownList(ProductType::getDropDownList(Tree::build(ProductType::lists(),'type_id','parent_id')), ['prompt' => '全部']) ?>
	<?= $form->field($model, 'model_id')->dropDownList(CategoryModel::getKeyValuePairs(Yii::$app->session->get('shop_id')), ['prompt' => '全部']) ?>
    <?= $form->field($model, 'status')->dropDownList($model->getStatusList(), ['prompt' => '全部','options'=>[$model->status=>['Selected'=>true]]]) ?>
	<?= $form->field($model, 'hot')->dropDownList(array('0'=>'否','1'=>'是'), ['prompt' => '全部','options'=>[$model->hot=>['Selected'=>true]]]) ?>
    <?= Html::submitButton(Html::icon('search'), ['class' => 'btn btn-primary btn-flat']) ?>
    <div class="error-summary hide"><ul></ul></div>

    <?php ActiveForm::end(); ?>

</div>
