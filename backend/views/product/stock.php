<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\assets\LayerAsset;
use common\models\StoreStock;
LayerAsset::register($this);
/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<?=Html::cssFile('@web/css/diy.css')?>
<div class="box box-primary">
    <div class="box-body">
   
   <table class="table table-bordered table-hover table-responsive product-table" id="product_table">
        <thead class="table_title">
            <tr>
                <?php foreach ($arr as $key=>$vo):?>
                     <th><em></em><?php echo $key; ?></th>
                <?php endforeach;?>
                <th><em></em>仓库</th>
                <th><em>*</em>库存(件)</th>
            </tr>
        </thead>
        <tbody class="list">   
     	    <?php foreach ($skus as $k=>$v):?>
     	    <?php foreach ($stores as $ss=>$store):?>
     	     	<tr>
     	     	<?php $myarr =  json_decode($v['sku_values'],true);?>
     	     	  <?php foreach ($myarr as $akey=>$a):?>
     	     	  		<td><?php echo $a[0];?></td>
     	     	  <?php endforeach;?>                                    	     	
                 <td><?php echo $store['name'];?></td>
                 <?php    $data=StoreStock::findOne(['store_id'=>$store['id'],'sku_id'=>$v['sku_id']]);
                 ?>
        		 <td><input name="stock" class="stock" data-sku_id="<?=$v['sku_id']?>" data-product_id="<?=$v['product_id']?>"   data-store_id="<?=$store['id']?>"  value="<?=$data['stock']?>" /></td>
        	<?php endforeach;?>
           <?php endforeach;?>
        </tbody>
    </table>
    <div class="form-group save-box">
    <button class="sub btn btn-primary btn-flat">确定</button>
    </div>              
</div>
<?php $this->beginBlock('sub') ?> 

    function sub(){
    	var data = new Array(); 
		$(".stock").each(function(){
			var sku_id = $(this).data('sku_id');
			var store_id = $(this).data('store_id');
			var stock = $(this).val();
			var product_id= $(this).data('product_id');
	        data.push({sku_id:sku_id,store_id:store_id,stock:stock,product_id:product_id})
	    });

    	$.ajax({
		  url: 'update-store-stock',
		  type: 'post',
		  data: {data:data},
		  dataType: 'json',
		  success: function (res) {
		    if(res.status == 1){
		    	alert('保存成功');
		    	window.refresh();
			}else{
				alert(res.msg);
			}
		  }
		})
	}
    $(".sub").click(function(){
		sub()
	})
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['sub'], \yii\web\View::POS_END); ?>