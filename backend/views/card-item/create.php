<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CardItem */

$this->title = 'Create Card Item';
$this->params['breadcrumbs'][] = ['label' => 'Card Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
