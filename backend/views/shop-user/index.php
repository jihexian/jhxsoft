<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ShopUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Shop Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?php $this->endBlock() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'username',
                    //'auth_key',
                   // 'password_hash',
                    //'password_reset_token',
                    // 'email:email',
                    // 'mobile',
                    // 'created_at',
                    // 'updated_at',
                    // 
                    // 'blocked_at',
                    // 'confirmed_at',
                    // 'access_token',
                    // 'expired_at',
              [
                'attribute' => 'level',
                'value' => function ($model) {
                return $model->level==0?'管理员':'店员';
                },
                ],
                   [
                        'attribute' => 'shop_id',
                        'value' => function ($model) {
                        return isset($model->shop->name)?$model->shop->name:'';
                        },
                    ],
                    [
                        'class' => 'backend\widgets\grid\SwitcherColumn',
                        'attribute' => 'status'
                    ],
                     'created_at:datetime',
                     'login_at',
                     [
                         'header'=>'操作',
                         'headerOptions'=>['width'=>'200'],
                         'class' => 'yii\grid\ActionColumn',
                         'template' => '{update} {view} {delete} {pwd}',
                         'buttons' => [
                             'pwd' => function($url, $model, $key) {
                             return Html::a('改密', null, ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip','href'=>Url::to(['shop-user/reset-password','id'=>$model->id]),'title' => '改密 ']);
                             }                             
                             ]
                       ],
  
                ],
            ]); ?>
        </div>
    </div>
