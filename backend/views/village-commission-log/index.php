<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VillageCommissionLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '扶贫记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' //. Html::a('Create Village Commission Log', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
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
                    //'m_id',
                    [
                        'label'=>'下单用户',
                        'value'=>function($model){
                        return $model['member']['username'];
                        }
                     ], 
                     [
                             'label'=>'店铺名',
                             'value'=>function($model){
                             return $model['shop']['name'];
                             }
                     ], 
//                     'shop_id',
                     'money',
                     'percentage',
                     [
                             'label'=>'扶贫村点',
                             'value'=>function($model){
                             return $model['village']['name'];
                             }
                    ], 
                    // 'desc',
                    // 'created_at',
                    // 'updated_at',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
