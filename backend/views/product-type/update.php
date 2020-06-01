<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductType */

$this->title = '更新类目: ' . ' ' . $model->type_id;
$this->params['breadcrumbs'][] = ['label' => 'Product Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->type_id, 'url' => ['view', 'id' => $model->type_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="product-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
