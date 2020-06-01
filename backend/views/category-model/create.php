<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CategoryModel */

$this->title = '添加模型';
$this->params['breadcrumbs'][] = ['label' => '商品模型', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-model-create">

    <?= $this->render('_form', [
        'model' => $model,
    		'modelAttr' => $modelAttr,
    		'modelAttrValue' => $modelAttrValue
    ]) ?>

</div>
