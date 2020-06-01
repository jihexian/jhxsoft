<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ProductComment */

$this->title = '回复评价';
$this->params['breadcrumbs'][] = ['label' => '评价列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-comment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
