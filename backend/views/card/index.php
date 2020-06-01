<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '卡券列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a('新增', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
    
	<div class="box box-primary">
        <div class="box-body"><?php  echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
        'columns' => [
                    'id',
                    'name',
                    [
                        'attribute'=>'type',
                        'value'=>function($model){
                            $types = $model->getTypes();
                        return $types[$model->type];
                        }
                    ],
                    'money',
                    //'created_at',
                    // 'updated_at',
                    // 'status',
                    [
                        'class' => 'backend\widgets\grid\SwitcherColumn',
                        'attribute' => 'status'
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}  {import} {update} {delete}',
                        'buttons' => [
                            'view' => function($url, $model, $key) {
                                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', Url::to(['/card-item/index','card_id'=>$model->id]), ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip', 'title' => '查看数据 ']);
                            },
                            'import' => function($url, $model, $key) {
                                return Html::a('<i class="fa fa-level-down"></i>', Url::to(['excel-import','id'=>$model->id]), ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip', 'title' => '导入数据 ']);
                            }
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
