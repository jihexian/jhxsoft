<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\VillageCommissionLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Village Commission Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'order_id',
            'order_no',
            'm_id',
            'shop_id',
            'money',
            'percentage',
            'village_id',
            'desc',
            'created_at',
            'updated_at',
        ],
    ]) ?>
    </div>
</div>
