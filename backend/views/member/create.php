<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Member */

$this->title = '添加会员';
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
