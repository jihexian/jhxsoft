<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Address */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Addresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uid',
            'userName',
            'postalCode',
            [
                'label' => '地址',
                'value' => function ($model) {
                return $model->province->name.$model->city->name.$model->county->name.$model->detailInfo;
                },
                ],
            'nationalCode',
            'telNumber',
            'status',
            'sort',
            'is_default',
        ],
    ]) ?>
    </div>
</div>
