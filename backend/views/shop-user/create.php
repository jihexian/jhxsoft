<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ShopUser */

$this->title = Yii::t('backend', 'Create Shop User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Shop Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-user-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
