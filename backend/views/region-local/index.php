<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RegionLocalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '地区列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title  ?>
<?php $this->endBlock() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?php //  GridView::widget([
//                 'dataProvider' => $dataProvider,
//                 'filterModel' => $searchModel,
//         'columns' => [
//                     'id',
//                     'code',
//                     'name',
//                     'parent_id',

//                     ['class' => 'yii\grid\ActionColumn'],
//                 ],
//             ]); ?>
 <?=  \backend\widgets\grid\TreeGrid::widget([
            'dataProvider' => $dataProvider,
            'keyColumnName' => 'id',
            'parentColumnName' => 'parent_id',
            'parentRootValue' => 0, //first parentId value
            'pluginOptions' => [
                'initialState' => 'collapsed',
            ],
            'columns' => [
                'name',  
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{create}{update}{delete}',
                    'buttons' => [
                        'create' => function($url, $model, $key) {
                            return Html::a('<i class="fa fa-plus"></i>', ['create', 'id' => $model->id], ['class' => 'btn btn-xs btn-default', 'data-toggle' => 'tooltip', 'title' => '添加子分类']);
                        }
                    ]
                ],
            ],
        ]); ?>
        </div>
    </div>
