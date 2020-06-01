<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ShopCategory */

$this->title = '新增行业';
$this->params['breadcrumbs'][] = ['label' => 'Shop Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
