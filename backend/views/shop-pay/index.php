<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ShopPaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '提现账号管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a('添加账号', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
        'columns' => [
                    'id',
             
                    'account',
                    //'name',
                     [
                        
                        'label'=>'收款人',
                        'value'=>function($model){
                        return $model['name']?$model['name']:'';
                        }
                     ],
                     [
                             
                         'label'=>'开户银行',
                         'value'=>function($model){
                          return $model['bank']?$model['bank']:'空';
                             }
                    ],
                    //'bank', 
                    [
                        'class' => 'backend\widgets\grid\SwitcherColumn',
                        'attribute' => 'status'
                    ],    

                    [
                        'label'=>'店铺',
                        'value'=>function($model){
                        return $model['shop']['name'];
                        }
                        ],
                    // 'sort',
                    // 'updated_at',
                    'created_at:date',

                    ['class' => 'yii\grid\ActionColumn',
                            'template' => '{update}{delete}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
