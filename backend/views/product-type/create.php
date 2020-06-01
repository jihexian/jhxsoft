<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ProductType */

$this->title = '新增类目';
$this->params['breadcrumbs'][] = ['label' => '商品类目', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    	//'modelAttr'=>$modelAttr,
    ]) ?>

</div>
