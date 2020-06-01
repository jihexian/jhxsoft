<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Address */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => '地址',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '地址'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="address-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
