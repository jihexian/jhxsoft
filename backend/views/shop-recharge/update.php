<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShopRecharge */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Shop Recharge',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shop Recharges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="shop-recharge-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
