<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Recharge */

$this->title = Yii::t('common', 'Update {modelClass}: ', [
    'modelClass' => 'Recharge',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Recharges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Update');
?>
<div class="recharge-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
