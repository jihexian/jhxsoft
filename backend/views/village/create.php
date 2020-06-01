<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\village */

$this->title = 'Create Village';
$this->params['breadcrumbs'][] = ['label' => 'Villages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="village-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
