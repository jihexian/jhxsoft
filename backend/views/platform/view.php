<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = '商品: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '全平台商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = '商品详情';
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
