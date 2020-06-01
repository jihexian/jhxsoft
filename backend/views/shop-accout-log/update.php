<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShopAccoutLog */

$this->title = Yii::t('common', 'Update {modelClass}: ', [
    'modelClass' => 'Shop Accout Log',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Shop Accout Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Update');
?>
<div class="shop-accout-log-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
