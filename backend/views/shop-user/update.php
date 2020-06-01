<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShopUser */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Shop User',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Shop Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="shop-user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
