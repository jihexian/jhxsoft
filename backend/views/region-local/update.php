<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RegionLocal */

$this->title = '修改地区: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '地区列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="region-local-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
