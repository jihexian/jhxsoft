<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Shipping */

$this->title = '修改运费模板: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '模板列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->shipping_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="shipping-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelFree' => $modelFree,
        'modelItem' => $modelItem,
        'modelsItem' => $modelsItem,
        'modelsFree' => $modelsFree,
    ]) ?>

</div>
