<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\Tools;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Order Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title ?>
<?php $this->endBlock() ?>
	<div class="box box-primary">
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel,'user'=>$user]); ?></div>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                   // 'id',
                    'order_no',
                  //  'status_desc',
                    'action_user:admin',
                  
                    [
                        'attribute' => 'order_status',
                        'value' => function($model) {
                        return Tools::get_status($model->order_status);
                        },
                        'enableSorting' => false
                        ],
                   // 'shipping_status',
                    // 'pay_status',
                     'action_note',
                     'create_time:datetime',
                    

                   
                ],
            ]); ?>
        </div>
    </div>
