<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PickSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Picks');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a(Yii::t('backend', 'Create Pick'), ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
          
        'columns' => [
                    'id',
                    'name',
                 /*    'province_id',
                    'city_id',
                    'area_id', */
              [
                        'label'=>'地址',
                        'attribute'=>'info',
                        'value' => function($model) {
                        return $model['province']['name'].$model['city']['name'].$model['area']['name'].$model->info;
                        }
                    ],  
                     'master',
                     'tel',
            [
                'class' => 'backend\widgets\grid\SwitcherColumn',
                'attribute' => 'status',
            ],
            [
                'class' => 'backend\widgets\grid\SwitcherColumn',
                'attribute' => 'is_free',
            ],
            
                     'created_at:datetime',
                    // 'updated_at',
                     'sort',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
