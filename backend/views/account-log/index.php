<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AccountLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Account Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' ?>
<?php $this->endBlock() ?>
	<div class="box box-primary">
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'member',
                    'money',
                    'change_money',
                    'score',
                    'change_score',                    
                    [
                	'attribute' => 'type',
                	'value' => function($model) {
                		return $model->renderType();
                	}
                	],
                	'user',
                    // 'desc',
                    // 'order_id',
                    // 'user_id',
                    // 'updated_at',
                	'created_at:datetime',
                    ['class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',                    
                    ],
                ],
            ]); ?>
        </div>
    </div>
