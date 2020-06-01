<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderArrive */

$this->title = 'Create Order Arrive';
$this->params['breadcrumbs'][] = ['label' => 'Order Arrives', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-arrive-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
