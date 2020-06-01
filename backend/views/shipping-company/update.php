<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShippingCompany */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Shipping Company',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Shipping Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="shipping-company-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
