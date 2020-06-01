<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Shipping */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shippings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'shipping_id',
            'name',
            'desc',
            'type',
            'shop_id',
            'sort',
            'status',
            'free_condition',
        ],
    ]) ?>
    </div>
</div>
