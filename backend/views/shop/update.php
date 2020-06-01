<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Shop */

$this->title = '店铺修改: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '店铺列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="shop-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
