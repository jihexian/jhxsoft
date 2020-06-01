<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\village */

$this->title = 'Update Village: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Villages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="village-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
