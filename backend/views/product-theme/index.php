<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductThemeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品主题';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a('新建主题', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
<div class="box box-primary">
<div class="box-body">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
</div>
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'name',
                    //'carousels:ntext',
                    //'bgim',
                    //'city',
                    // 'district',
                    // 'town',
                    // 'village',
                    // 'created_at',
                    // 'updated_at',
                    [
                        'class' => 'backend\widgets\grid\SwitcherColumn',
                        'attribute' => 'status'
                    ],
                    [
                        'class' => 'backend\widgets\grid\PositionColumn',
                        'attribute' => 'sort'
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
