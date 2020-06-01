<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ShopUser */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Shop Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'mobile',

            'level',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                return $model->status==1?'启用':'禁用';
                },
                ],
            [
                'attribute' => 'shop_id',
                'value' => function ($model) {
                return isset($model->shop->name)?$model->shop->name:'';
                },
                ],
                'created_at:datetime',
                'updated_at:datetime',
                'login_at:datetime',
                'blocked_at',
        ],
    ]) ?>
    </div>
</div>
