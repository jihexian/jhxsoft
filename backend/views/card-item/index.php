<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CardItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '充值卡列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' ?>
<?php $this->endBlock() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'card_no',
                    [
                        'attribute'=>'password',
                        'value'=>function ($model) {
                            return Yii::$app->security->decryptByPassword(base64_decode($model->password),$model->card_no);
                        }
                    ],
                    'card.name',
                    [
                        'label'=>'拥有者',
                        'attribute'=>'member.username',
                    ],
                    'created_at:datetime',
                    'use_time:datetime',
            
                    // 'updated_at',
                    // 'version',
                    // 'info:ntext',
                    // 'password',
                    

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
