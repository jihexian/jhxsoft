<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderRefundDoc */

$this->title = Yii::t('common', 'Create Order Refund Doc');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Order Refund Docs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-refund-doc-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
