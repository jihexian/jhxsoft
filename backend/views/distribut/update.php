<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Distribut */

$this->title = 'Update Distribut: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Distributs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="distribut-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
