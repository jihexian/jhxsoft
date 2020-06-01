<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ShopRecharge */

$this->title = Yii::t('common', 'Create Shop Recharge');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Shop Recharges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-recharge-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
