<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ShopRecharge */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Shop Recharges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'order_no',
            'shop_id',
            'pay_amount',
            'score',
            'payment_code',
            'payment_name',
            'created_at',
            'updated_at',
            'pay_status',
            'transaction_id',
        ],
    ]) ?>
    </div>
</div>
