<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

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
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'name',
                      [
                        'attribute'=>'village_id',
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
                        'label'=>'审核状态',
                        'value'=>function($model){
                        return $model->applyStatus($model->apply_status);
                        }
                        ], 
                    // 'lng',
                    // 'lat',
                    // 'mobile',
                    // 'description',
                    // 'license',
                    // 'idcard',
                    // 'type', 
                [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {pass} {close}',
                        'buttons' => [
                          
                        'pass' => function($url, $model, $key) {
                        return Html::a('<i class="fa fa-check"></i>',Url::to(['shop/approve','id'=>$model->id]), ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip', 'title' => '通过','data-confirm' => '确认审核通过吗?' ]);
                         },
                         'close' => function($url, $model, $key) {
                         return Html::a('<i class="fa fa-close"></i>',Url::to(['shop/refuse','id'=>$model->id]), ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip', 'title' => '驳回']);
                         }
                         
                     ]
                 ],
                ],
            ]); ?>
        </div>
    </div>
