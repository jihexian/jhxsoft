<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2020年4月30日下午3:27:43
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use common\helpers\Tree;
use common\models\ProductType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = '添加商品';
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">
    <?php $form = ActiveForm::begin(
    		[
    			'id' => 'form-id',  
    		    'method' => 'get',
    		    'action' => ['product/create'],

    		]); ?>
       <div class="form-group">
                    <label class="col-xs-2"><em>*</em>所属类目：</label>
                    <div class="col-xs-10">
                        <div>
                        <?= $form->field($model, 'type_id', ['template' => '{input}{error}','options' => ['tag=>false']])->dropDownList(ProductType::getDropDownList(Tree::build(ProductType::lists(),'type_id','parent_id')), ['class'=>'form-control width200']) ?>  
                        </div>                    
                        <small style=""></small>
                    </div>     
                </div>
                
         <div class="form-group save-box">
					<?= Html::submitButton('下一步填写商品信息', ['class' =>'btn btn-primary btn-flat']) ?>
				</div>          
  
     <?php ActiveForm::end(); ?>

</div>
