<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CategoryModel */

$this->title = '修改模型: ' . ' ' . $model->model_name;
$this->params['breadcrumbs'][] = ['label' => '商品模型', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->model_name, 'url' => ['view', 'id' => $model->model_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="category-model-update">

    <?= $this->render('_form', [
        'model' => $model,
    		'modelAttr' => $modelAttr,
    		'modelAttrValue' => $modelAttrValue
    ]) ?>

</div>
