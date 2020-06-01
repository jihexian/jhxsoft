<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderArriveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Order Arrives';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a('Create Order Arrive', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'order_no',
                    'pay_amount',
                    'm_id',
                    'payment_status',
                    // 'shop_id',
                    // 'is_shop_checkout',
                    // 'order_price',
                    // 'created_at',
                    // 'updated_at',
                    // 'user_id',
                    // 'remark',
                    // 'payment_no',
                    // 'payment_name',
                    // 'paytime:datetime',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
