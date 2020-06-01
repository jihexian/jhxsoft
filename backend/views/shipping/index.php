<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '模板列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a('添加模板', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'shipping_id',
                    'name',
                    //'desc',
                    //'type',
                    //'shop_id',
                    //'sort',
                    // 'status',
                    // 'free_condition',
                    [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}'
                    ],
                ],
            ]); ?>
        </div>
    </div>
