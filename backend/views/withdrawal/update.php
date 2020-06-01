<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Withdrawal */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Withdrawal',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Withdrawals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="withdrawal-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
