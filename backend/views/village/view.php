<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\village */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Villages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code',
            'name',
            'address',
            'phone',
            'contact',
            'province_id',
            'city_id',
            'district_id',
            'money',
            'count',
            'created_at',
            'updated_at',
            'sort',
            'status',
        ],
    ]) ?>
    </div>
</div>
