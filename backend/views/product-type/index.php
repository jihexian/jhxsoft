<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品类目';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a('添加类目', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>

    <div class="box box-primary">
        <div class="box-body">
           <?= \backend\widgets\grid\TreeGrid::widget([
            'dataProvider' => $dataProvider,
            'keyColumnName' => 'type_id',
            'parentColumnName' => 'parent_id',
            'parentRootValue' => 0, //first parentId value
            'pluginOptions' => [
                'initialState' => 'collapsed',//expanded or collapsed
            ],
            'columns' => [
                'type_name',
            	[
            		'class' => 'backend\widgets\grid\SwitcherColumn',
            		'attribute' => 'status'
            	],
       
                [
                    'class' => 'backend\widgets\grid\PositionColumn',
                    'attribute' => 'sort'
                ],
            		
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{create}{update} {delete}',
                    'buttons' => [
                        'create' => function($url, $model, $key) {
                            return Html::a('<i class="fa fa-plus"></i>', ['create', 'type_id' => $model->type_id], ['class' => 'btn btn-xs btn-default', 'data-toggle' => 'tooltip', 'title' => '添加子分类']);
                        }
                    ]
                ],
            ],
        ]); ?>
        </div>
    </div>
