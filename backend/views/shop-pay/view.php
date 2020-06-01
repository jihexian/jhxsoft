<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ShopPay */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shop Pays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',

            'acount',
            'name',
            'bank',
            'status',
            'sort',
            'updated_at',
            'created_at',
        ],
    ]) ?>
    </div>
</div>
