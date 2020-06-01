<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderLog */

$this->title = Yii::t('common', 'Create Order Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Order Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-log-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
