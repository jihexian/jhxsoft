<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShopCategory */

$this->title = '修改行业: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shop Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改行业';
?>
<div class="shop-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
