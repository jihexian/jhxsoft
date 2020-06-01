<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Recharge */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Recharges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'order_no',
                [
                'attribute' => 'm_id',
                'value' => function($model) {
                return $model->getUserName();
                }
             ],
            'pay_amount',
            'payment_name',
            'created_at',
            'updated_at',
            'pay_status',
        ],
    ]) ?>
    </div>
</div>
