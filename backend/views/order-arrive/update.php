<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderArrive */

$this->title = 'Update Order Arrive: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Order Arrives', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-arrive-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
