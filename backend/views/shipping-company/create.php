<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ShippingCompany */

$this->title = Yii::t('common', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Shipping Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shipping-company-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
