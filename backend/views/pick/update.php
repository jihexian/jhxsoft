<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Pick */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Pick',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Picks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="pick-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
