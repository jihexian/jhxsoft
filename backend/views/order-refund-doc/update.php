<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderRefundDoc */

$this->title = Yii::t('common', 'Update {modelClass}: ', [
    'modelClass' => 'Order Refund Doc',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Order Refund Docs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Update');
?>
<div class="order-refund-doc-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
