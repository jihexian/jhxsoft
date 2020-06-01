<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductCommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评价列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title  ?>
<?php $this->endBlock() ?>
 	<div class="box box-primary">
        <div class="box-body"><?php  echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>   

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
        'columns' => [
                    'comment_id',
                    'order_no',
                    'member.username',
                    'orderSku.goods_name',
                    [
                    'attribute' => 'content',
                    'value' => function($model) {
                    	return mb_substr($model->content, 0,20).'...';
                    }
                    ],                     
                     'created_at:datetime',                    
                    [
                        'class' => 'backend\widgets\grid\SwitcherColumn',
                        'attribute' => 'status'
                    ],
                    [
                	'attribute' => 'reply_status',
                	'value' => function($model) {
                		return $model->renderReplyStatus();
                	}
                	],
                	[
                	'attribute' => 'appraise',
                	'value' => function($model) {
                		return $model->renderAppraise();
                	}
                	],
                    // 'total_stars',
                    // 'des_stars',
                    // 'delivery_stars',
                    // 'service_stars',

                    [
                    	'class' => 'yii\grid\ActionColumn',
                		'template' => '{create} {view}{delete}',
                    	'buttons' => [
                        'create' => function($url, $model, $key) {
                            return Html::a('<i class="fa fa-plus"></i>', ['create', 'comment_id' => $model->comment_id], ['class' => 'btn btn-xs btn-default', 'data-toggle' => 'tooltip', 'title' => '回复']);
                        },
//                         'update' => function($url, $model, $key) {
//                         	return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'comment_id' => $model->comment_id], ['class' => 'btn btn-default btn-xs', 'data-toggle' => 'tooltip', 'title' => '回复修改']);
//                         }
                    ]
                	],
                ],
            ]); ?>
        </div>
    </div>
