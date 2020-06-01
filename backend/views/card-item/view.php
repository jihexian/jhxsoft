<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CardItem */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Card Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            'use_time:datetime',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
    </div>
</div>
