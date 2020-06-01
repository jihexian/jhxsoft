<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会员列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a('添加会员', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
	<div class="box box-primary">
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'username',
                    'mobile',
                    //'email',
                    'sex',
                    //'age',
                    'score',
                    'user_money',
                    [
					'attribute' => 'type',
                     'value'=>function($model){
                            return $model->renderType();
                            },
					'label' => '会员类型',
					],
                    [
                        'class' => 'backend\widgets\grid\SwitcherColumn',
                        'attribute' => 'status'
                    ],
                  
                    //'register_time:datetime',
                    //'last_login:datetime',
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{view}'.' '.'{update}'.' '.' '.'{pwd}',
                        'buttons' => [
                          
                            'pwd' => function($url, $model, $key) {
                                return Html::a('改密', null, ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip','href'=>Url::to(['member/pwd','id'=>$model->id]),'title' => '改密 ']);
                            }
                            ]
                        ],
                ],
            ]); ?>
        </div>
    </div>
