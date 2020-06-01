<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Village;


/* @var $this yii\web\View */
/* @var $searchModel common\models\ShopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商家列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' '. Html::a('新增店铺', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
	<div class="box box-primary">
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'name',
                      [
                        'attribute'=>'category',
                        'label'=>'行业',
                        'value'=>function($model){
                        return $model['category']['name']?$model['category']['name']:'无';
                        }
                     ], 
                 
            
                    //'logo',
                    //'image',
                    // 'created_at',
                    // 'updated_at',

                    [
                        'attribute'=>'type',
                        'label'=>'店铺类型',
                        'value'=>function($model){
                        return $model->renderStatus();
                        }
                    ], 
                   
                    [
                        'attribute'=>'type',
                        'label'=>'店铺类型',
                        'value'=>function($model){
                        return $model->renderStatus();
                        }
                     ], 
                    [
                        'class' => 'backend\widgets\grid\SwitcherColumn',
                        'attribute' => 'status'
                    ],     
                    [
                        'class' => 'backend\widgets\grid\PositionColumn',
                        'attribute' => 'sort'
                    ],

                  
                    [
                        'attribute'=>'type',
                        'label'=>'审核状态',
                        'value'=>function($model){
                        return $model->applyStatus($model->apply_status);
                        }
                        ], 
                    // 'lng',
                    // 'lat',
                    // 'description',
                    // 'license',
                    // 'idcard',
                    // 'type', 
                [ 
                       'header'=>'操作',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {prev} {view} {manager}',
                        'buttons' => [
                           'prev' => function($url, $model, $key) {
                            return Html::a('<i class="fa fa-credit-card "></i>',Url::to(['shop/record','id'=>$model->id]), ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip', 'title' => '资产明细']);
                            },
                            'manager' => function($url, $model, $key) {
                            return Html::a('账号', null, ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip','href'=>Url::to(['shop-user/manager','id'=>$model->id]),'title' => '帐号 ']);
                            }
                            
                     ]
                 ],
                 
            
                ],      
            ]); ?>
        </div>
    </div>
