<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShopPay */

$this->title = '修改店铺提现账号信息: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shop Pays', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shop-pay-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
