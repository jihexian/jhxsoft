<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=Html::cssFile('@web/css/order.css')?>
<div class="box box-primary">
    <div class="box-body">
    	<div class="edit-box">
    	    <?php $form = ActiveForm::begin(); ?>
    		
	    	<table class="table table-striped">
	    		<tbody>
	    			<tr>
	    				<td>商品总价</td>
	    				<td><?=$model['sku_price_real']?></td>
	    			</tr>
	    			<tr>
	    				<td>物流运费</td>
	    				<td>  <?= $form->field($model, 'delivery_price_real')->textInput(['maxlength' => true]) ?>
	    				</td>
	    			</tr>
	    			<tr>
	    				<td>订单价格微调</td>
	    				<td>
	    				 <?= $form->field($model, 'discount_price')->textInput(['maxlength' => true]) ?>
	    				
	    					<small>请直接输入要调整的金额, 如果是正数价格下调, 负数价格上调</small>
	    				</td>
	    			</tr>
	    			<tr>
	    				<td>订单总金额</td>
	    				
	    				<td><?=$model['order_price']?></td>
	    			</tr>
	    			<tr>
	    				<td>使用余额</td>
	    				
	    				<td><?=$model['user_money']?></td>
	    			</tr>
	    			<tr>
	    				<td>使用积分</td>
	    				<td><?=$model['integral']?></td>
	    			</tr>
	    			<tr>
	    				<td>积分兑换金额</td>
	    				<td><?=$model['integral_money']?></td>
	    			</tr>
	    			<tr>
	    				<td>应付金额</td>
	    			
	    				<td><?=$model['pay_amount']?> 
	    				</td>
	    					
	    			</tr>
	    		</tbody>
			</table>
           
           
    <div class="btn-box">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-xs  btn-flat' : 'btn btn-primary btn-xs btn-flat']) ?>
    </div>
    <div style="display:none "><?=$form->field($model, 'id')->hiddenInput(['value'=>$model['id']]) ?> </div>	
    <?php ActiveForm::end(); ?>
    	</div>

    </div>
</div>
