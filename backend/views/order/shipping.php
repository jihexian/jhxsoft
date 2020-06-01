<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年12月18日 下午6:37:50
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Html;
use common\helpers\Tools;
use yii\helpers\Url;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '发货单';
/* $this->params['breadcrumbs'][] = $this->title; */
?>
<?php $this->beginBlock('content-header') ?>
<?php $this->endBlock() ?>
 

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
               'columns' => [
                    'id',
                     [
                      'attribute' => 'm_id',
                      'value' => function($model) {
                          return Tools::get_user_name($model->m_id);
                      }
                  ],
                    'order_no',
                      'full_name',
                      'tel',
                  [
                      'attribute' => 'payment_status',
                      'value' => function($model) {
                          return Tools::pay_status($model->payment_status);
                      }
                  ],
                        // 'payment_no',
                    // 'delivery_id',
                    // 'delivery_time',
                    // 'delivery_status',
                    // 'shop_id',
                    // 'is_shop_checkout',
                  [
                      'attribute' => 'status',
                      'value' => function($model) {
                      return Tools::shipping_status($model->delivery_status);
                      }
                  ],

                    // 'prov',
                    // 'city',
                    // 'area',
                    // 'address',
                    // 'sku_price',
                    // 'sku_price_real',
                    // 'delivery_price',
                    // 'delivery_price_real',
                    // 'discount_price',
                    // 'promotion_price',
                    // 'coupons_price',
                     'pay_amount',
                    // 'coupons_id',
                    // 'm_desc',
                    // 'admin_desc',
                     'create_time:datetime',
                    // 'paytime:datetime',
                    // 'sendtime:datetime',
                    // 'completetime:datetime',
                    // 'is_del',
                    // 'update_time:datetime',

                  [
                      'class' => 'yii\grid\ActionColumn',
                      'header' => '操作',
                      'options' => ['width' => '100px;'],
                      'template' => '{view} {send}',
                      'buttons' => [
                          'send' => function($url, $model, $key) {
                          return Html::a('<i class="fa fa-send"></i>',Url::to(['order/delivery','id'=>$model->id]) , ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip', 'title' => '发货']);
                          }
                        ]
                   ]
                ],
            ]); ?>
        </div>
    </div>

