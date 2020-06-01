<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
//         'attributes' => [
//             'product_id',
//             'name',
//             'model_id',
//             'cat_id',
//             'type_id',
//             'brand_id',
//             'up_time:datetime',
//             'down_time:datetime',
//             'create_at',
//             'update_at',
//             'image',
//             'unit',
//             'status',
//             'visit',
//             'favorite',
//             'sortnum',
//             'comments',
//             'sale',
//             'shop_id',
//             'max_price',
//             'min_price',
//             'stock',
//         ],
    ]) ?>
    </div>
</div>
