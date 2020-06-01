<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Shop */

$this->title = 'Create Shop';
$this->params['breadcrumbs'][] = ['label' => 'Shops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
