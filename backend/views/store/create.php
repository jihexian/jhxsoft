<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Store */

$this->title = Yii::t('common', 'Create Store');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Stores'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
