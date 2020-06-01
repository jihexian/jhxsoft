<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Distribut */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Distributs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'level',
            'pid',
            'cid',
        ],
    ]) ?>
    </div>
</div>
