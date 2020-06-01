<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MemberLevel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Member Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'pid',
            'name',
            'sort',
            'status',
            'create_at',
        ],
    ]) ?>
    </div>
</div>
