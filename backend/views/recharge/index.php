<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RechargeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Recharges');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?php echo $this->title ?>
<?php $this->endBlock() ?>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'order_no',
                    [
                    'attribute' => 'm_id',
                    'value' => function($model) {
                    return $model->getUserName();
                    }
                    ],
                    'pay_amount',
                   
                     'payment_name',
                     'created_at:datetime',
                    // 'updated_at',
                   [
                    'attribute' => 'pay_status',
                    'value' => function($model) {
                       return $model->payStatus($model->pay_status);
                    }
                    ],

                    [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{view}'
                    ]
                ],
            ]); ?>
        </div>
    </div>
