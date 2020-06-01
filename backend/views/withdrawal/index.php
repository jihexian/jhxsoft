<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\Tools;
/* @var $this yii\web\View */
/* @var $searchModel common\models\WithdrawalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Withdrawals');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?php echo  $this->title?>
<?php $this->endBlock() ?>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
               // 'filterModel' => $searchModel,
        'columns' => [
                    'id',
                     [
                        'attribute' => 'm_id',
                        'value' => function($model) {
                        return $model->getUserName();
                        }
                     ],
                    'pay_amount',
                    'created_at:datetime',
                   // 'updated_at',
                    // 'pay_time:datetime',
                     'bank_name',
                     'bank_card',
                    // 'realname',
                    // 'remark',
                    // 'taxfee',
                 [
                'attribute' => 'status',
                'value' => function($model) {
                   return $model->getStatus($model->status);
                }
                ],
                    // 'transaction_id',
                    // 'error_code',

                [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{view} '
                ],
                ],
            ]); ?>
        </div>
    </div>
