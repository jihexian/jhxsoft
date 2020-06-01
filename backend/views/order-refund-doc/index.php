<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\Tools;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderRefundDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Order Refund Docs');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title ?>
<?php $this->endBlock() ?>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                  //  'order_id',
                    [
                    'attribute' => 'm_id',
                    'value' => function($model) {
                    return $model['member']['username'];
                    }
                    ],
                    //'goods_id',
                    //'sku_id',
                    
                     'addtime:datetime',
                  
                     [
                         'attribute' => 'status',
                         'value'=>function ($model,$key,$index,$column){
                         return Tools::refuse_status($model->status);
                         }
                      ],
                     'out_refund_no',
                     'admin_user:admin',
                     //'message:ntext',
                    // 'shop_id',
                     'amount',
                     [
                         'class' => 'yii\grid\ActionColumn',
                         'header' => '操作',
                         'options' => ['width' => '100px;'],
                         'template' => '{view} ',
                         'buttons' => [
                           'refund' => function($url, $model, $key) {
                             return Html::a('<i class="fa fa-gavel"></i>', null, ['class' => 'btn btn-xs btn-default','onclick'=>"copyStr($model->id)",'data-toggle' => 'tooltip', 'title' => '发货']);
                             }
                       ]
                ],
                ],
            ]); ?>
        </div>
    </div>
