<?php

use yii\grid\GridView;
use common\models\Member;
use common\models\OrderSku;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分销抽成记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-log-index">
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    [
                     'attribute' => 'pid',
                     'label'=>'用户名',
                     'value' => function($model) {
                     return $model['member']['username'];
                        }
                    ],
                    [
                       'attribute' => 'cid',
                       'label'=>'下级用户',
                        'value' => function($model) {
                        return $model['subMember']['username'];
                        }
                   ],
                    'level',
                    [
                      'attribute' => 'goods_id',
                      'label'=>'商品名称',
                      'value' => function($model) {
                      return $model['product']['name'];        
                      }
                    ],
                    'change_money',
                    [
                            'attribute' => 'updated_at',
                            'label'=>'时间',
                            'value' => function($model) {
                            return date('Y-m-d H:m:s',$model['updated_at']);
                            }
                            ],
                    [
                      'attribute' => 'status',
                      'label'=>'状态',
                      'value' => function($model) {
                         $status=$model->status;
                         $data=$status==1?'已获得':($status==2?'在路上':'失败');
                        return $data;
                       }
                    ],
                  ],
            ]); ?>
        </div>
    </div>

</div>
