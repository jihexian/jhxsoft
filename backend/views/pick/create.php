<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Pick */

$this->title = Yii::t('backend', 'Create Pick');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Picks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pick-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
