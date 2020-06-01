<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评论';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .comment-index img{width:80px;height:80px;margin-right:10px;}
</style>
<div class="comment-index">
    <?= $this->render('_search', ['model' => $searchModel]) ?>
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'member_id',
                        'value' => function($model) {
                            return $model->member->username;
                        }
                    ],
                    'user_ip',
                    [
                        'label' => '源',
                        'value' => function ($model) {
                            return \common\helpers\Util::getEntityName($model->entity) . ':' . $model->entity_id;
                        }
                    ],
                    [
                        'attribute' => 'content',
                        'options' => ['width' => '40%'],
                        'value' => function($model) {
                            return \yii\helpers\Markdown::process($model->content);
                        },
                        'format' => 'html'
                    ],
                    'created_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                    	//'template' => '{create} {view} {delete}',
                        //'template' => '{update} {delete} {ban}',
                    		'template' => '{view} {delete}',
                        'buttons' => [
//                             'ban' => function($url, $model, $key) {
//                                 return Html::a(Html::icon('ban'),
//                                     ['/user/admin/block', 'id' => $model->user_id],
//                                     [
//                                         'class' => 'btn btn-default btn-xs',
//                                         'title' => '封禁用户',
//                                         'data-confirm' => '确定要封禁用户吗?',
//                                         'data-ajax' => '1',
//                                         'data-method' => 'post'
//                                     ]
//                                 );                                
//                             },
//                             'create' => function($url, $model, $key) {
//                             	return Html::a('<i class="fa fa-plus"></i>', ['create', 'id' => $model->id], ['class' => 'btn btn-xs btn-default', 'data-toggle' => 'tooltip', 'title' => '回复']);
//                             },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>
<?php $this->beginBlock('js') ?>
<script>
    layer.ready(function () {
        layer.photos({
            photos:'.comment-index',
            shift:5
        });
    })
</script>
<?php $this->endBlock() ?>