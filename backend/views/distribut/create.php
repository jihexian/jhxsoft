<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Distribut */

$this->title = 'Create Distribut';
$this->params['breadcrumbs'][] = ['label' => 'Distributs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="distribut-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
