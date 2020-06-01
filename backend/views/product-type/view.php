<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ProductType */

$this->title = $model->type_id;
$this->params['breadcrumbs'][] = ['label' => 'Product Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'type_id',
            'parent_id',
            'shop_id',
            'type_name',
            'remark',
            'display_order',
            'is_system',
            'keyword',
            'discription',
            'seo_content',
        ],
    ]) ?>
    </div>
</div>
