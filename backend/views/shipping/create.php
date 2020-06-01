<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Shipping */

$this->title = '添加模板';
$this->params['breadcrumbs'][] = ['label' => '模板列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shipping-create">

    <?= $this->render('_form', [
        'model' => $model,
    		'modelFree' => $modelFree,
    		'modelItem' => $modelItem,
    ]) ?>

</div>
