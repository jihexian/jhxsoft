<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Card */

$this->title = '修改卡券: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '卡券列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="card-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
