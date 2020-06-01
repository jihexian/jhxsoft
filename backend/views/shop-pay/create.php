<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ShopPay */

$this->title = '新增店铺提现账号信息';
$this->params['breadcrumbs'][] = ['label' => 'Shop Pays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-pay-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
