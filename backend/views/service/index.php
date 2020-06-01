<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Services');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title?>
<?php $this->endBlock() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                            'label'=>'会员',
                            'attribute' => 'mid',
                            'value' => function($model) {
                            return $model->getMemberName();
                            }
                            ],
                  //  'order_id',
                    [
                                'label'=>'商品',
                                'attribute' => 'sku_id',
                                'value' => function($model) {
                                return $model->getGoodsName();
                                }
                                ],
                     [
                        'attribute' => 'type',
                        'value' => function($model) {
                        return $model->getType();
                        }
                     ],
                   // 'company',
                    'delivery_no',
                    // 'mark',
                    // 'created_at',
                    // 'apply_status',
                    // 'user_id',
                    // 'receive_status',
                  
                        [
                        'attribute' => 'status',
                        'value' => function($model) {
                        return $model->renderStatus();
                        }
                     ],
                    // 'amount',
                    // 'refund_type', 
                    
                    // 'shop_id',
                    // 'name',
                    // 'mobile',
                     'created_at:datetime', 
                     [
                             'class' => 'yii\grid\ActionColumn',
                             'template'=>'{view}'
                     ],
                ],
            ]); ?>
        </div>
    </div>
