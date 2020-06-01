<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\StoreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Stores');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a(Yii::t('common', 'Create Store'), ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'name',
                   // 'code',
                    'tel',
                    'addr',
                    // 'sort',
                    // 'created_at',
                    // 'updated_at',
            [
                'class' => 'backend\widgets\grid\SwitcherColumn',
                'attribute' => 'status'
            ],

               [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {view} {update} '
                ],
           ]]); ?>
        </div>
    </div>
