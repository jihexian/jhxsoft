<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MemberLevel */

$this->title = 'Update Member Level: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Member Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="member-level-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
