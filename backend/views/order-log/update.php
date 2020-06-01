<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderLog */

$this->title = Yii::t('common', 'Update {modelClass}: ', [
    'modelClass' => 'Order Log',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Order Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Update');
?>
<div class="order-log-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
