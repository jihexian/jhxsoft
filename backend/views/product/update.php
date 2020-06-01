<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = '修改商品: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = '修改';
?>

<div class="product-update">

    <?= $this->render('_form', [
        		'model' => $model,
    			'attribute' => $attribute,
    			'attributeValue' => $attributeValue,
    			'modelSkus' => $modelSkus,
    			'skus'=>$skus,
    			'skuList'=>$skuList,
    			'attributes'=>$attributes,
    			'attributeValues'=>$attributeValues,
    			'modelProductModelAttr' => $modelProductModelAttr,
    			'modelCategoryModelAttr' => $modelCategoryModelAttr,
    			'productModelAttrs' =>$productModelAttrs
    ]) ?>

</div>
