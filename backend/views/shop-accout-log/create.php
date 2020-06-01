<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ShopAccoutLog */

$this->title = Yii::t('common', 'Create Shop Accout Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Shop Accout Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-accout-log-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
