<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OrderArrive */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Order Arrives', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'order_no',
            'pay_amount',
            'm_id',
            'payment_status',
            'shop_id',
            'is_shop_checkout',
            'order_price',
            'created_at',
            'updated_at',
            'user_id',
            'remark',
            'payment_no',
            'payment_name',
            'paytime:datetime',
        ],
    ]) ?>
    </div>
</div>
