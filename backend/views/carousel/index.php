<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Carousel');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?php // $this->title . ' ' . Html::a('新幻灯片', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?= $this->title?>
<?php $this->endBlock() ?>
<div class="box box-primary">
    <div class="box-body">


<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [

        'id',
        'key',
        'title',
       /*  [
            'class' => 'backend\widgets\grid\SwitcherColumn',
             'attribute' => 'status',
             'reload' => 0
        ],
        [
            'class' => 'backend\widgets\grid\SwitcherColumn',
            'attribute' => 'is_platform',
            'reload' => 0
        ], */
        [
            'class' => 'yii\grid\ActionColumn',
            //'template'=>'{update} {delete}',
        	'template'=>'{update}',
        	
        ],
    ],
]); ?>
    </div>
</div>
