<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = '添加商品';
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <?= $this->render('_form', [
        'model' => $model,    	
    	'attribute' => $attribute,
        'attributes'=>$attributes,//初始化指定类目下的规则值
    	'attributeValue' => $attributeValue,
    	'modelSkus' => $modelSkus,
    	'modelProductModelAttr' => $modelProductModelAttr,
    	'modelCategoryModelAttr' => $modelCategoryModelAttr,
    ]) ?>

</div>
