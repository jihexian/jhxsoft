<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Recharge */

$this->title = Yii::t('common', 'Create Recharge');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Recharges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recharge-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
