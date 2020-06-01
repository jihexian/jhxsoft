<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RegionLocal */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Region Locals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code',
            'name',
            'parent_id',
        ],
    ]) ?>
    </div>
</div>
