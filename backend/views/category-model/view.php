<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CategoryModel */

$this->title = $model->model_id;
$this->params['breadcrumbs'][] = ['label' => 'Category Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'model_id',
            'category_id',
            'model_name',
            'status',
        ],
    ]) ?>
    </div>
</div>
