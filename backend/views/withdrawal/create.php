<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Withdrawal */

$this->title = Yii::t('backend', 'Create Withdrawal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Withdrawals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="withdrawal-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
