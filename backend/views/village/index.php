<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VillageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '村点列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a('添加村点', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
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
                    'code',
                    'name',
                    'address',
                    'phone',
                    'contact',
                    // 'province_id',
                    // 'city_id',
                    // 'district_id',
                    // 'created_at',
                    // 'updated_at',
                    // 'sort',
                    // 'status',

                    ['class' => 'yii\grid\ActionColumn',
                            'template' => '{view}{update}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
