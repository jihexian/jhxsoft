<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Card */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute'=>'type',
                'value'=>function($model){
                    $types = $model->getTypes();
                    return $types[$model->type];
                }
            ],
            'money',
            //'created_at',
            //'updated_at',
            //'status',
        ],
    ]) ?>
    </div>
</div>
