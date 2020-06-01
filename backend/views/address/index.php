<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AddressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '地址');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title ?>
<?php $this->endBlock() ?>
    <?php  //  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'uid',
                    'userName',
                    //'postalCode',
                    [
                        'label' => '地址',
                        'value' => function ($model) {
                        return $model->province->name.$model->city->name.$model->county->name.$model->detailInfo;
                        },
                    ],
                 
                    // 'nationalCode',
                    // 'telNumber',
                    // 'status',
                    // 'sort',
                    // 'is_default',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
