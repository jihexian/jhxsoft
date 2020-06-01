<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ProductTheme */

$this->title = '新建主题';
$this->params['breadcrumbs'][] = ['label' => '主题列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-theme-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
