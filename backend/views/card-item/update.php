<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CardItem */

$this->title = 'Update Card Item: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Card Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="card-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
