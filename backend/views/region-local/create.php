<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\RegionLocal */

$this->title = '新增';
$this->params['breadcrumbs'][] = ['label' => '地区列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-local-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
