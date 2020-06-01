<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Store */

$this->title = Yii::t('common', 'Update {modelClass}: ', [
    'modelClass' => 'Store',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Stores'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Update');
?>
<div class="store-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
