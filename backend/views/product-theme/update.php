<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductTheme */

$this->title = '修改主题: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '主题列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-theme-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
