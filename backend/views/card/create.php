<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Card */

$this->title = 'Create Card';
$this->params['breadcrumbs'][] = ['label' => 'Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
