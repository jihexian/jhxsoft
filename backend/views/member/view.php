<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Member */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '会员列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'mobile',
            //'password',
            //'xcx_openid',
            //'wx_openid',
            'sex',
            'age',
            'score',
            'user_money',
            'memberLevel.name',
            [
                'attribute' => 'avatarUrl',
                'value' =>function($model) {
                    $image = $model->avatarUrl;               
                    return Html::img($image,['width'=>'100px']);
                },
                'format' => ['html'],
                ],
            'register_time:datetime',
            'last_login:datetime',
        ],
    ]) ?>
    </div>
</div>
