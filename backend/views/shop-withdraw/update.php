<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShopWithdraw */

$this->title = '更新: ' . ' ' . $model->realname;
$this->params['breadcrumbs'][] = ['label' => '提现审核', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->realname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="shop-withdraw-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
