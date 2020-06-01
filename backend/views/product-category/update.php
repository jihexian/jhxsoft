<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductCategory */

$this->title = '修改分类: ' . ' ' . $model->cat_name;
$this->params['breadcrumbs'][] = ['label' => '商品分类', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cat_name, 'url' => ['view', 'id' => $model->category_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="product-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
