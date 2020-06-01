<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member;
use common\models\Shop;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ShopCommissionLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '平台抽成记录表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' //. Html::a('Create Shop Commission Log', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<div class="box box-primary">
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
//                 'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    //'order_id',
                    'order_no',
                     [
                        'attribute' => 'm_id',
                        'label'=>'下单用户',
                        'value'=>function($model){
                        return $model['member']['username'];
                        }
                     ],
                    [
                       'attribute' => 'shop_id',
                       'label'=>'店铺名',
                        'value'=>function($model){
                        return $model['shop']['name'];
                            }
                      ],
                    // 'money',
                     'percentage',
                    // 'desc',
                    // 'created_at',
                     [
                        'attribute' => 'updated_at',
                        'label'=>'时间',
                        'value'=>function($model){
                        return date('Y-m-d H:m:s',$model['updated_at']);
                             }
                      ],
                ],
            ]); ?>
        </div>
    </div>
