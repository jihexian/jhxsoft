<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductComment */

$this->title = 'Update Product Comment: ' . ' ' . $model->comment_id;
$this->params['breadcrumbs'][] = ['label' => 'Product Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->comment_id, 'url' => ['view', 'id' => $model->comment_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-comment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
