<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DistributLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Distribut Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'pid',
            'cid',
            'level',
            'goods_id',
            'change_money',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>
    </div>
</div>
