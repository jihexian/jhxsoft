<?php

use yii\helpers\Html;
use common\models\ProductCategory;
use common\helpers\Tree;
use common\models\ProductType;
use common\models\Attribute;
use common\models\AttributeValue;
use common\models\CategoryModel;
use common\modules\attachment\widgets\SingleWidget;
use yii\widgets\ActiveForm;
use common\models\Shipping;
use common\models\Product;


/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<?=Html::cssFile('@web/css/diy.css')?>
<?php $this->registerJsFile('@web/js/common.js',['depends' => 'backend\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]); ?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(
    		[
    			'id' => 'form-id',    			
    			'enableAjaxValidation' => true,
    			'validateOnChange'=>false,
    			'validateOnBlur'=>false,
    			'validateOnSubmit' => true
    		]); ?>
    <?php $this->beginBlock('specification') ?>  
    var baseUrl = '<?php echo Yii::$app->config->get('SITE_URL')?>';   			
    $(function(){
        //规格名称赋值input   
		$(".specNameSelect").change(function(){
			if($(this).val()!=0){			
				if(!checkAttr($(this).find("option:selected").text())){
					alert('规格名称重复！');		
					return;
				}else{
					$(this).parent().siblings("input").first().val($(this).find("option:selected").text());      									 
       				setTable(1);  
				}
       		}    		   			
		});
		$('#product-model_id').change(function(){
			var model_id = $(this).val();
			//console.log('model_id'+model_id);
			$.ajax({ 
				type : "POST", //提交方式 
			    url : baseUrl+'/api/v1/category-model/detail',//路径 
			    data : { 
			    	"model_id" : model_id
			    }, 
			    success : function(data) {			    	
			    	console.log(data);
			    	setProductModel(data);			    
			    } 
			});
		
		});
		//切换模型
		function setProductModel(data){
			$('#attributeContainer').empty();
			var items = data.items;
			
			var categoryModelAttr = items[0].categoryModelAttr;
			if(categoryModelAttr==null){
				$('#attributeContainer').hide();				
				return;
			}	
			
			var html = '<label class="col-xs-2">商品属性：</label><div class="col-xs-10"><div class="property-box">';		
			for(var i=0;i<categoryModelAttr.length;i++){
				var type = categoryModelAttr[i].type;
				var categoryModelAttrValue = categoryModelAttr[i].categoryModelAttrValue;	
				if(type==1||type==3){
					
					html += '<div class="propertyitem"><span class="glyphicon glyphicon-trash propertymovebtn"></span><div class="attr-name">'+categoryModelAttr[i].attr_name+
					'<input value="'+categoryModelAttr[i].model_attr_id+'" type="hidden"  class="form-control" name="ProductModelAttr['+i+'][0][model_attr_id]"></div>'+
                    '<select class="propertysel form-control width200" name="ProductModelAttr['+i+'][0][model_attr_value_id]">'+
                    '<option value="">请选择</option>';
                    for(var j=0;j<categoryModelAttrValue.length;j++){
                        html += '<option value="'+categoryModelAttrValue[j].model_attr_value_id+'"selected="selected">'+categoryModelAttrValue[j].value_str+'</option>';    		                        	
	                }
	                html += '</select></div>';           	
                        	
				}else if(type==2){
					html += '<div class="propertyitem propertycheckbox"><span class="glyphicon glyphicon-trash propertymovebtn"></span><div class="attr-name">'+categoryModelAttr[i].attr_name+'</div>';					
					for(var j=0;j<categoryModelAttrValue.length;j++){					
						html += '<label><input value="'+categoryModelAttrValue[j].model_attribute_id+'" type="hidden"  class="form-control" name="ProductModelAttr['+i+']['+j+'][model_attr_id]">'+
                        '<input type="checkbox" name="ProductModelAttr['+i+']['+j+'][model_attr_value_id]" value="'+categoryModelAttrValue[j].model_attr_value_id+'">'+categoryModelAttrValue[j].value_str+'</label>';                        	
					}
					html += '</div>';   
				}else if(type==4){
					html += '<div class="propertyitem"><span class="glyphicon glyphicon-trash propertymovebtn"></span><div class="attr-name">'+categoryModelAttr[i].attr_name+
					'<input value="'+categoryModelAttr[i].model_attr_id+'" type="hidden"  class="form-control" name="ProductModelAttr['+i+'][0][model_attr_id]"></div></div>'
					+'<input type="text" placeholder="" class="form-control width200" name="ProductModelAttr['+i+'][attr_value]">'
				}
								
				//var categoryModelAttrValue = categoryModelAttr[i].categoryModelAttrValue;
				//console.log(categoryModelAttr[i].attr_name);
				//for(var j=0;j<categoryModelAttrValue.length;j++){					
					//console.log(categoryModelAttrValue[j].value_str);				
				//}	
			}
			html += '</div>';     
			$('#attributeContainer').html(html);
			$('#attributeContainer').show();	
		}
		
		$(".specifications-box").on("keyup",".attribute",function(){  
			var count = 0;
			var attr =  $(this).val();
			$(".attribute").each(function(i){
				if(i>0){					
					if($(this).val()==attr)					
						count++;
				}				
			});
			if(count>1){			
				alert('规格名称重复！');
				$(this).val($(this).attr("oldvalue"));	
				setTable(1);	
			}else{
				setTable(1);  
			}
			
        });   
		
	
		//检查规格名称是否有重复  f重复 t不重复）
		function checkAttr(attr){
			var flag = true;
			$(".attribute").each(function(i){
				if(i>0){					
					if($(this).val()==attr)					
						flag = false;
				}				
			});
			return flag;
		}			
			
        //增加规格值
        $(".specifications-box").on("click",".specvaluebtn",function(){ 
        	var d = $(this).parents('.specificationitem').find("#specname").eq(0);
        	console.log(d);
        	var attrValue = $(this).parents('.specificationitem').find("#specname").eq(0).val();
        	console.log(attrValue);
        	if(attrValue==null||attrValue==undefined||attrValue==""){
        		alert("规格名称不能为空！");
        		return;
        	}  
        	
            var specval = $(this).siblings(".attribute-value").val();
            var lab = "<label class='specvaluelabel'>"+specval+"<i class='fa fa-times removelab' aria-hidden='true'></i></label>";
        	if(specval!=""){
           		$(this).parent().siblings(".specnvalue-box").append(lab);
           		<!-- $(this).parents('.addBox').prev('.label-b').append(lab); -->
         	}else{
				alert("规格值不能为空！")
       		}
     		$(this).siblings(".attribute-value").val("");
     		setTable();
        });       
        
      
		//删除规格值
        $(".specifications-box").on("click",".removelab",function(){
            $(this).parent().remove();
            setTable();
        });  
        
        //删除整行规格
        $(".specifications-box").on("click",".glyphicon-trash",function(){
        	if($(".specificationitem").length<=2){
        		alert('必须保留一个规格值');
        		return;
        	}
            $(this).parent().remove();
            setTable(2);
        });  
       
        //新增规格
        $("#btn-newcataddspec").click(function(){       
        	var cloneItem = $(".specificationitem").first().clone(true);        	
        	$(".specificationitem").last().after(cloneItem);
        	$(".specificationitem").last().show();
        }); 
    });
    
    
		//全局th变量
        var thString = $("thead tr").html();            
        function setTable(type,image=0,setsku=0){
        	//type==1时只更改表头
        	if(type==1){
	        	var attributes = new Array();
	        	var th = ""
	        	$(".attribute").each(function(i){
	        		//表头
	        		if(i>0){
	        		    var tst= '<?php echo str_replace("\n", '', trim($form->field($attribute, "[rowsize]attribute_name",['template' => '{input}{error}','options' => ['tag=>false' ]])->hiddenInput())) ?>';
	        		    tst = tst.replace(/rowsize/g,(i-1));
	        		    tst = tst.replace(/input/g,'input value="'+$(this).val()+'"');
	        			th += '<th>' + $(this).val() + tst +'</th>'
	        		}		
	        		$("thead").html(th+thString);   
	        		if(i>0){
		        		////fixFormValidaton('form-id','attribute',i-1,'attribute_name');
	        		}     		
	        	});
	        	return;
        	}else if(type==2){//同时更改表头
        		var attributes = new Array();
	        	var th = ""
	        	$(".attribute").each(function(i){
	        		//表头
	        		if(i>0){
	        		    var tst= '<?php echo str_replace("\n", '', trim($form->field($attribute, "[rowsize]attribute_name",['template' => '{input}{error}','options' => ['tag=>false' ]])->hiddenInput())) ?>';
	        		    tst = tst.replace(/rowsize/g,(i-1));
	        		    tst = tst.replace(/input/g,'input value="'+$(this).val()+'"');
	        			th += '<th>' + $(this).val() + tst +'</th>'
	        		}		
	        		$("thead").html(th+thString);  
	        		if(i>0){
		        		//fixFormValidaton('form-id','attribute',i-1,'attribute_name');
	        		}       		
	        	});
        	}
        	
        	//表内容
        	var trData = "";        	
        	var valueArray = new Array();//规格值二位数组
        	$(".specnvalue-box").each(function(k){  
        		if(k>0){
	        		valueArray[k] = new Array();
	        		$($(this).children(".specvaluelabel")).each(function(){
	        			valueArray[k].push($(this).text());
	        		});   
        		}    			
        	});        	
        	//计算规格值rowspan        	
        	var rowspan = new Array();
        	for(var i=1;i<valueArray.length;i++){
        		var rowspanCount = 1;
        		$.each(valueArray,function(k){
        			if(k>i){
        				rowspanCount = rowspanCount * valueArray[k].length;        				
        			}
        		});
        		
        		rowspan.push(rowspanCount);
        	}
        	
        	var column = $(".attribute").length-1;  
        	var tdnext = "";   
        	var dataArr=valueArray;  //去掉第一个空的数组
		    dataArr.shift();
		    var rs=rank(dataArr); //排列出所有可能的组合
		    var boxIds = new Array();
		    if(typeof(rs)!='undefined'){
			if(rs[0] instanceof Array){
				for(var i=0;i<rs.length;i++){					
	        		trData += "<tr>";   
	        		tdnext = '';
		        	for(var j=0;j<rs[i].length;j++){			        	
		        		if(i%rowspan[j]==0){
		        			//tdnext +="<td rowspan='"+ rowspan[j]+"'>"+rs[i][j]+"</td>";
		        			var tst= '<?php echo str_replace("\n", '', trim($form->field($attributeValue, "[rowsize][column]value_str",['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput())) ?>';
	        		    	tst = tst.replace(/column/g,j);
	        		    	tst = tst.replace(/rowsize/g,i);
	        		    	tst = tst.replace(/input/g,'input value="'+rs[i][j]+'"');
		        			tdnext +="<td rowspan='"+ rowspan[j]+"'>"+rs[i][j]+tst+"</td>";
		        		}else{
		        			//tdnext +="<td style='display:none'>"+rs[i][j]+"</td>";	
		        			var tst= '<?php echo str_replace("\n", '', trim($form->field($attributeValue, "[rowsize][column]value_str",['template' => '{input}{error}','options' => ['tag=>false' ]])->hiddenInput())) ?>';
	        		    	tst = tst.replace(/column/g,j);
	        		    	tst = tst.replace(/rowsize/g,i);
	        		    	tst = tst.replace(/input/g,'input value="'+rs[i][j]+'"');
		        			tdnext +="<td style='display:none'>"+rs[i][j]+tst+"</td>";		
		        		}
		        	}
		        	var price = '<?php echo str_replace("\n", '', trim($form->field($modelSkus, "[rowsize]sale_price",['template' => '{input}{error}','options' => ['tag=>false' ]])->textInput(['class'=>'form-control']))) ?>';
		        	var plus_price = '<?php echo str_replace("\n", '', trim($form->field($modelSkus, "[rowsize]plus_price",['template' => '{input}{error}','options' => ['tag=>false' ]])->textInput(['class'=>'form-control']))) ?>';
		        	var stock = '<?php echo str_replace("\n", '', trim($form->field($modelSkus, "[rowsize]stock",['template' => '{input}{error}','options' => ['tag=>false' ]])->textInput(['class'=>'form-control']))) ?>';
		        	price = price.replace(/rowsize/g,i);
		        	plus_price = plus_price.replace(/rowsize/g,i);
		        	stock = stock.replace(/rowsize/g,i);
		        	if(!image){		        	
			        	var img = '<?php echo str_replace("\n", '', trim($form->field($modelSkus, '[rowsize]image')->widget(SingleWidget::className(),['onlyUrl' => true,'thumb'=>1,'width'=>40,'height'=>40,'autoJquery'=>false]))) ?>';
			        	var imgDom = $(img);		        	
			        	var hash =  imgDom.find('.upload-kit input').eq(0).attr('id');
						var reg = "/"+hash+"/g";					 
						img = img.replace(eval(reg),hash+(i+1).toString());  
			        	img = img.replace(/rowsize/g,i);			        	
			        	trData += tdnext + '<td>'+price+'</td>'+ '<td>'+plus_price+'</td>'+'<td>'+stock+'</td>'+ '<td>'+img+'</td>'+'</tr></tr>';  	
			        	tdnext = "";
			        	var box_id = hash+(i+1).toString(); 
			        	boxIds.push(box_id);
		        	}else{
		        		trData += tdnext + '<td>'+price+'</td>'+ '<td>'+plus_price+'</td>'+'<td>'+stock+'</td></tr>'; 		 
		        		tdnext = "";
		        	}
		        	
	        	}
			}else{
				for(var i=0;i<rs.length;i++){ 
				
					trData += "<tr>";   
					tdnext = "";
					var tst= '<?php echo str_replace("\n", '', trim($form->field($attributeValue, "[rowsize][column]value_str",['template' => '{input}{error}','options' => ['tag=>false' ]])->hiddenInput())) ?>';
	        		tst = tst.replace(/column/g,0);
	        		tst = tst.replace(/rowsize/g,i);
	        		tst = tst.replace(/input/g,'input value="'+rs[i]+'"');
					tdnext +="<td>"+rs[i]+tst+"</td>";				
					var price = '<?php echo str_replace("\n", '', trim($form->field($modelSkus, "[rowsize]sale_price",['template' => '{input}{error}','options' => ['tag=>false' ]])->textInput(['class'=>'form-control']))) ?>';
					var plus_price = '<?php echo str_replace("\n", '', trim($form->field($modelSkus, "[rowsize]plus_price",['template' => '{input}{error}','options' => ['tag=>false' ]])->textInput(['class'=>'form-control']))) ?>';
		        	var stock = '<?php echo str_replace("\n", '', trim($form->field($modelSkus, "[rowsize]stock",['template' => '{input}{error}','options' => ['tag=>false' ]])->textInput(['class'=>'form-control']))) ?>';
		        	price = price.replace(/rowsize/g,i);
		        	plus_price = plus_price.replace(/rowsize/g,i);
		        	stock = stock.replace(/rowsize/g,i);	      
		        	if(!image){
			        	var img = '<?php echo str_replace("\n", '', trim($form->field($modelSkus, '[rowsize]image')->widget(SingleWidget::className(),['onlyUrl' => true,'thumb'=>1,'width'=>100,'height'=>100,'autoJquery'=>false]))) ?>';
			        	var imgDom = $(img);			        	
			        	var hash =  imgDom.find('.upload-kit input').eq(0).attr('id');
						var reg = "/"+hash+"/g";  
						img = img.replace(eval(reg),hash+(i+1).toString());  
			        	img = img.replace(/rowsize/g,i);		        		  			
						trData += tdnext + '<td>'+price+'</td>'+ '<td>'+plus_price+'</td>'+'<td>'+stock+'</td>'+ '<td>'+img+'</td>'+'</tr></tr>'; 
						var box_id = hash+(i+1).toString(); 
						boxIds.push(box_id);
		        	}else{
		        		trData += tdnext + '<td>'+price+'</td>'+ '<td>'+plus_price+'</td>'+'<td>'+stock+'</td></tr>'; 		
		        	}		        	
					//console.log('skus-'+i+'-image');
					//console.log("Skus["+i+"][image]");
				}				
			}        	
        	$('.list').html(trData);
        	if(setsku==0){
	        	if(rs[0] instanceof Array){
					for(var i=0;i<rs.length;i++){	        		
			        	for(var j=0;j<rs[i].length;j++){
			        		fixMultipleFormValidaton('form-id','attributevalue',i,j,'value_str');        		
			        	}
			        	fixFormValidaton('form-id','skus',i,'sale_price');
			        	fixFormValidaton('form-id','skus',i,'plus_price');
			        	fixFormValidaton('form-id','skus',i,'stock');	        	
		        	}
				}else{
					for(var i=0;i<rs.length;i++){ 
		        		fixMultipleFormValidaton('form-id','attributeValue',i,0,'value_str');     	
						fixFormValidaton('form-id','skus',i,'sale_price');
						fixFormValidaton('form-id','skus',i,'plus_price');
			        	fixFormValidaton('form-id','skus',i,'stock');
					}				
				}
        	}
        	
			
        	if(!image){        	
	        	for(var i=0;i<rs.length;i++){  
	        		//console.log(boxIds);
	        		var boxId = boxIds[i];      
	        		//console.log(boxId);	
	        		jQuery('#'+boxId).attachmentUpload({
							"id":'skus-'+i+'-image',
							"name":"Skus["+i+"][image]",
							"url":baseUrl + "/admin/upload/image-upload?fileparam="+boxId+"&thumb=1&width=320&height=320",
							"multiple":false,
							"sortable":false,
							"maxNumberOfFiles":1,
							"maxFileSize":null,
							"acceptFileTypes":null,
							"files":[],
							"onlyUrl":true,
							"isNew":true
							}
						);
				}
			}
        }
        }

        <?php if (!empty($skus)):?>
   		var skus =  '<?php echo $skus?>';
   		skus = JSON.parse(skus);
   		//alert(skus);   
        setTable(2,1,1);
        setSkus(skus);//更新时设置sku
        function setSkus(skus){
        	var trs = $('#product_table tr');
        	var th = $('#product_table th');
        	for(var i=0;i<trs.length;i++){
        		var tr = trs[i];
        		var tds = $(tr).find('td');
        		var specificationitems = $('.specificationitem');
        		var sku_ids = '';
        		for(var j=0;j<tds.length-2;j++){
        			var tdValue = $(tds[j]).text();
        			//alert(tdValue);
        			var labels = $('.specificationitem').eq(j+1).find('.specvaluelabel');
        			for(var m=0;m<labels.length;m++){
        				if(tdValue==$(labels[m]).text()){
        					var valueId = $(labels[m]).attr('data');
		        			if(sku_ids==''){
		        				sku_ids = valueId;
		        			}else{
		        				sku_ids = sku_ids+'_'+valueId;
		        			}
        				}
        			}        			
        		}
        		//console.log(sku_ids);
        		for(var k=0 ;k<skus.length;k++){
        			var sku = skus[k];
        			var skuIds = sku.sku_id;
        			var skuIdDiv = "#"+skuIds;        			 
        			skuIds = skuIds.substring(skuIds.indexOf("_")+1);
        			//console.log("skuIds"+skuIds+'sku_ids'+sku_ids); 
        			if(skuIds==sku_ids){        			
        				//alert(sku.sale_price);
        				$(tr).find('input').eq(tds.length-3).val(sku.sale_price);  
        				$(tr).find('input').eq(tds.length-2).val(sku.plus_price);      				
        				$(tr).find('input').eq(tds.length-1).val(sku.stock);
        				$(tr).append('<td></td>');         			       			
	        			$(skuIdDiv).children().clone().prependTo($(tr).find('td').last());        			
	        			$(skuIdDiv).remove();	        			    			
        			}        	
        		}
        	}
        }
        
        <?php endif?> 
        
        
        /*返回组合的数组*/
		function rank(arr){
	        var len = arr.length;
	        // 当数组大于等于2个的时候
	        if(len >= 2){
	            // 第一个数组的长度
	            var len1 = arr[0].length;
	            // 第二个数组的长度
	            var len2 = arr[1].length;
	            // 2个数组产生的组合数
	            var lenBoth = len1 * len2;
	            //  申明一个新数组
	            var items = new Array(lenBoth);
	            // 申明新数组的索引
	            var index = 0;
	            for(var i=0; i<len1; i++){
	                for(var j=0; j<len2; j++){
	                    if(arr[0][i] instanceof Array){
	                        items[index] = arr[0][i].concat(arr[1][j]);
	                    }else{
	                        items[index] = [arr[0][i]].concat(arr[1][j]);
	                    }
	                    index++;
	                }
	            }
	            var newArr = new Array(len -1);
	            for(var i=2;i<arr.length;i++){
	                newArr[i-1] = arr[i];
	            }
	            newArr[0] = items;
	            return rank(newArr);  //递归调用
	        }else{
	        	
	            return arr[0];
	        }
		}
<?php $this->endBlock() ?>  
<?php $this->registerJs($this->blocks['specification'], \yii\web\View::POS_END); ?>
        <ul class="nav nav-tabs">
          <li role="presentation" class="active"><a href="#">商品信息</a></li>
          <!-- <li role="presentation"><a href="#">编辑商品详情</a></li> -->
        </ul>
        <div class="tab-pane">
            <div class="form-horizontal">
                <h3>基本信息</h3> 
                <div class="form-group">
                    <label class="col-xs-2"><em>*</em>商品名称：</label>
                    <div class="col-xs-10">
                        <div>
                        	 <?= $form->field($model, 'name', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['maxlength' => true,'class'=>'form-control width200']) ?>
                        </div>                    
                        <small style=""></small>
                    </div>     
                </div>   
                <div class="form-group">
                    <label class="col-xs-2"><em>*</em>所属类目：</label>
                    <div class="col-xs-10">
                        <div>
                        <?= $form->field($model, 'type_id', ['template' => '{input}{error}','options' => ['tag=>false']])->dropDownList(ProductType::getDropDownList(Tree::build(ProductType::lists(),'type_id','parent_id')), ['class'=>'form-control width200']) ?>  
                        </div>                    
                        <small style=""></small>
                    </div>     
                </div>
                <div class="form-group">
                    <label class="col-xs-2 mt8"><em>*</em>商品分类：</label>
                    <div class="col-xs-10">
                        <div>
                        <?= $form->field($model, 'cat_id', ['template' => '{input}{error}','options' => ['tag=>false']])->dropDownList(ProductCategory::getDropDownList(Tree::build(ProductCategory::lists(null,Yii::$app->session->get('shop_id')),'category_id','parent_id')), ['prompt' => '请选择','class'=>'form-control width200']) ?>
                        </div>                    
                    </div>                
                </div>
                 <div class="form-group">
                    <label class="col-xs-2"><em>*</em>分销百分比(%)：</label>
                    <div class="col-xs-10">
                        <div>
                        	 <?= $form->field($model, 'distribute_money', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['maxlength' => true,'class'=>'form-control width200']) ?>
                        </div>                    
                        <small style=""></small>
                    </div>     
                </div>
                <div class="form-group">
                    <label class="col-xs-2 mt8"><em>*</em>商品图片：</label>
                    <div class="col-xs-10">
                        <div class="bigImg">
                        	<div style="display:flex;align-items:flex-end ">
                        		<?php echo $form->field($model, 'image')->widget(\common\modules\attachment\widgets\MultipleWidget::className(), ['onlyUrl' => true,"multiple"=>true,"sortable"=>true,'thumb'=>1,'width'=>500,'height'=>500]) ?>
                        		<div class="box-des"><span>图片上传尺寸建议为500*500像素</span></div>  
                        	</div>                   		
                		</div>
                    </div>                
                </div>
                <div class="form-group">
                    <label class="col-xs-2">模型名称：</label>
                    <div class="col-xs-10">
                        <div>
                        <?= $form->field($model, 'model_id', ['template' => '{input}','options' => ['tag'=>false]])->dropDownList(CategoryModel::getKeyValuePairs(), ['prompt' => '请选择','class'=>'form-control width200 modelselect']) ?>                       
                        </div>                    
                    </div>                
                </div>               
                    
                <div class="form-group" id='attributeContainer' <?php if(count($modelCategoryModelAttr)==0){echo 'style="display:none"';} ?>>
                 	<?php if(count($modelCategoryModelAttr)>0):?>
                    <label class="col-xs-2">商品属性：</label>
                    <div class="col-xs-10">                    
                        <div class="property-box">                      
                       <?php foreach ($modelCategoryModelAttr as $i => $attr): ?>  
                       <?php $attrValues = $attr->categoryModelAttrValue;?>
                                           
                       <?php if ($attr->type==2):?>
                        	<div class="propertyitem propertycheckbox">
                        	<span class="glyphicon glyphicon-trash propertymovebtn"></span>
                        	<div class="attr-name"><?php echo $attr->attr_name?>                        	
                        	</div>
                        	<?php foreach ($attr->categoryModelAttrValue as $j => $value): ?> 
                        	<label>
                        	<input value="<?php echo $value->model_attribute_id?>" type="hidden"  class="form-control" name="ProductModelAttr[<?php echo $i?>][<?php echo $j?>][model_attr_id]">
                        	<input type="checkbox" name="ProductModelAttr[<?php echo $i?>][<?php echo $j?>][model_attr_value_id]" value="<?php echo $value->model_attr_value_id?>"
	                        	<?php if (isset($productModelAttrs)):?>
	                        		
		                        	<?php foreach ($productModelAttrs as $pattr): ?> 
		                        		<?php if ($pattr->model_attr_value_id==$value->model_attr_value_id):?>
		                        		checked="checked"
		                        		<?php endif?>
		                        	<?php endforeach; ?>
	                        	<?php endif?>
                        	><?php echo $value->value_str?></label>
                        	<?php endforeach; ?>
                        	</div>                        	
                      
                       <?php endif?>  
                       
                       <?php if ($attr->type==3||$attr->type==1):?>  
                        	<div class="propertyitem">
                        	<span class="glyphicon glyphicon-trash propertymovebtn"></span>
                        	<div class="attr-name"><?php echo $attr->attr_name?>                        	
                        	<input value="<?php echo $attr->model_attr_id?>  " type="hidden"  class="form-control" name="ProductModelAttr[<?php echo $i?>][0][model_attr_id]"></div>
                        	<select class="propertysel form-control width200" name="ProductModelAttr[<?php echo $i?>][0][model_attr_value_id]">
                        	<option value="">请选择</option>
                        	<?php foreach ($attr->categoryModelAttrValue as $j => $value): ?> 
                        	<option value="<?php echo $value->model_attr_value_id?>"
                        	<?php if (isset($productModelAttrs)):?>
		                        	<?php foreach ($productModelAttrs as $pattr): ?> 
		                        		<?php if ($pattr->model_attr_value_id==$value->model_attr_value_id):?>
		                        		selected="selected"
		                        		<?php endif?>
		                        	<?php endforeach; ?>
	                        	<?php endif?>
                        	>
                        	<?php echo $value->value_str?></option>
                        	<?php endforeach; ?>
                        	</select>
                        	</div>
                        	
                       <?php endif?>
                       <?php if ($attr->type==4):?><!-- 暂时无用 -->  
                        	<div class="propertyitem">
                        	<span class="glyphicon glyphicon-trash propertymovebtn"></span>
                        	<div class="attr-name"><?php echo $attr->categoryModelAttr->attr_name?>             
                        	<input value="<?php echo $attr->model_attr_id?>  " type="hidden"  class="form-control" name="ProductModelAttr[<?php echo $i?>][0][model_attr_id]"></div>                        	           	
                        	</div>
                        	<input type="text" placeholder="" class="form-control width200" name="ProductModelAttr[<?php echo $i?>][attr_value]">
                       <?php endif?>		
                       <?php endforeach; ?>                       
                       </div>
                       <?php endif?>  
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2">商品规格：
                    <div class="field-skus-info required" 0="tag=>false">
					<input type="hidden" id="skus-info" class="form-control width200"  maxlength="100" aria-required="true"><div class="help-block"></div>
					</div>
                    </label>
                    
                    <div class="col-xs-10">
                        <div class="specifications-box">
                            <div class="specificationitem" style="display: none">
                                <div class="pdtb10 relative width120" style="height: 52px;">
                                    <input type="text" name="" placeholder="规格名称" class="form-control width100 attribute"  id="specname">
                                    <span class="selectbox">
                                        <select class="form-control specNameSelect">
                                        </select>
                                    </span>
                                </div>
                                <div class="saleproperty-box labelwidth">
                                    <label class="saleproperty">
                                        <div class="specnvalue-box">

                                        </div>
                                        <div class="addBox relative">
                                            <input type="text" placeholder="规格值" class="addInput form-control attribute-value" >
                                            <span class="selectbox top0" >
                                                <select data="" class="form-control specValueSelect">
                                                </select>
                                            </span>
                                            <span class="btn btn-primary btn-flat btn-xs specvaluebtn">+</span>
                                        </div> 
                                    </label>
                                </div>
                                <span class="glyphicon glyphicon-trash"></span>
                            </div>
                            <?php if (isset($attributes)):?>  
                            <?php foreach ($attributes as $i=>$attr): ?> 
                            <div class="specificationitem">
                                <div class="pdtb10 relative width120" style="height: 52px;">
                                    <input type="text"  placeholder="规格名称" class="form-control width100 attribute" value="<?php echo $attr->attribute_name?>" id="specname">
                                    <span class="selectbox">
                                        <select class="form-control specNameSelect">
                                        </select>
                                    </span>
                                </div>
                                <div class="saleproperty-box labelwidth">
                                    <label class="saleproperty">
                                        <div class="specnvalue-box">
                                            <?php foreach ($attributeValues as $j=>$value): ?> 
                                            <?php if ($value->attribute_id==$attr->attribute_id):?>  
                                            <label class="specvaluelabel" data="<?php echo $value->value_id?>"><?php echo $value->value_str?><i class="fa fa-times removelab" aria-hidden="true"></i></label>
                                            <?php endif?>		
                                            <?php endforeach; ?>
                                        </div>
 					                    <div class="addBox relative">
	                                        <input type="text" placeholder="规格值" class="addInput form-control attribute-value" >
	                                        <span class="selectbox top0" >
	                                            <select data="" class="form-control specValueSelect">
	                                            </select>
	                                        </span>
	                                        <span class="btn btn-primary btn-flat btn-xs specvaluebtn">+</span>
	                                    </div>
                                    </label>
                                </div>
                                <span class="glyphicon glyphicon-trash"></span>
                            </div>
                            <?php endforeach; ?>
                            <?php endif?>		
                            <div class="form-group">
                                <div class="text-right borderdashed">
                                    <span class="btn btn-primary addSpecifications" id="btn-newcataddspec">+ 新增规格</span>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 40px;">
                                <label class="pr0 pl0 fl">销售规格:</label>
                                <span style="color: #ff4444;float: right;font-size: 12px;display: inline-block;">图片上传尺寸建议为500*500像素</span>
                                <div class="col-xs-9">
                                    <div style="color: #a9a9a9;display: none">（该类目下：颜色尺寸，请全选或不选，如果只选一部分无法保证库存及价格）</div>
                                </div>
                            </div>
                            <div class="form-group" style="display: none">
                                <label class="pr0 pl0 fl mt5">批量填充</label>
                                <div class="col-xs-10" id="div-fillspec">
                                    <input class="width80 mr10 form-control" type="text" placeholder="价格" id="priceSet">
                                    <input class="width80 mr10 form-control" type="text" placeholder="数量" id="numSet">
                                    <input class="width100 mr10 form-control" type="text" placeholder="编码" id="codeSet">
                                    <input class="width100 mr20 form-control" type="text" placeholder="条形码" id="barSet">
                                    <input class="btnconfirm btn btn-primary btn-flat btn-xs" type="button" value="确定" onclick="SetAll()" id="btn-fillspec">
                                </div>
                            </div>
                            <table class="table table-bordered table-hover table-responsive product-table" id="product_table">
                                <thead>
                                    <tr>
                                        <th><em>*</em>价格(元)</th>
                                        <th><em></em>站长价格(元)</th>
                                        <th><em>*</em>库存(件)</th>
                                        <th>图片</th>                                        
                                        <!-- <th>编码</th> 
                                        <th>条形码</th>-->
                                    </tr>
                                </thead>
                                <tbody class="list">                        
                                <tr>                                     
                                    
                                </tr>
                                
                                </tbody>
                            </table>
                            <?php if(!empty($skuList)):?>
							<?php foreach ($skuList as $i=>$sku): ?> 
							 <div id='<?php echo $sku['sku_id']?>' >
							  <?= $form->field($sku, "[{$i}]image")->widget(SingleWidget::className(),['onlyUrl' => true,'thumb'=>1,'width'=>100,'height'=>100]) ?>
							 </div>
							<?php endforeach; ?>
							<?php endif?>	
                            
                            
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<label class="col-xs-2">商品详情：</label>
                	<div class="col-xs-10 productdetail">
                		<?= $form->field($model, 'content')->widget(\common\widgets\EditorWidget::className(), $model->isNewRecord ? ['type' => request('editor') ? : config('page_editor_type')] : ['isMarkdown' => $model->markdown]) ?>
                	</div>
		        </div>
		        <div class="form-group">
                	<label class="col-xs-2">上架时间：</label>
                	<div class="col-xs-10">
                		<?= $form->field($model, 'up_time',['template' => '{input}{error}','options' => ['tag=>false','class'=>' width200' ]])->widget(
                        \kartik\datetime\DateTimePicker::className(),
                        [
                            'type' => 1,
                            'options' => [
                                'value' => !empty($model->up_time) ? date('Y-m-d H:i:s', $model->up_time) : date('Y-m-d H:i:s', time()),  

                            ],
                            'pluginOptions' => ['autoclose' => true],
                            
                        ]
                    ) ?>
                    </div>
		        </div>
		        <div class="form-group">
                	<label class="col-xs-2">是否热销：</label>
                	<div class="col-xs-10">
                		<?= $form->field($model, 'hot',['template' => '{input}{error}','options' => ['tag=>false' ]])->radioList(array(1=>'是',0=>'否'))?>
                    </div>
		        </div>
		        <div class="form-group">
                	<label class="col-xs-2">是否免邮：</label>
                	<div class="col-xs-10">
                		<?= $form->field($model, 'is_free',['template' => '{input}{error}','options' => ['tag=>false' ]])->radioList(array(1=>'是',0=>'否'))?>
                    </div>
		        </div>	        
		        <div class="form-group">
                    <label class="col-xs-2">运费模板：</label>
                    <div class="col-xs-10">
                        <div>
                        	<?= $form->field($model, 'shipping_id', ['template' => '{input}{error}','options' => ['tag=>false']])->dropDownList(Shipping::getKeyValuePairs(), ['class'=>'form-control width200 modelselect']) ?>  
                        </div>                    
                        <small style=""></small>
                    </div>     
                </div>
                <div class="form-group">
                    <label class="col-xs-2">商品状态：</label>
                    <div class="col-xs-10">
                        <div>
                        	<?= $form->field($model, 'status', ['template' => '{input}','options' => ['tag'=>false]])->dropDownList(Product::getStatusList(), ['class'=>'form-control width200 modelselect']) ?>  
                        </div>                    
                        <small style=""></small>
                    </div>     
                </div>
                <div class="form-group save-box">
				</div>
            </div>
        </div>
    </div>
     <?php ActiveForm::end(); ?>
     <?php $this->beginBlock('initSkus') ?>  
     window.onload=function(){ 
     	fixValidation();
     	var attributes = ['info']
     	fixFormSingleValidatons('form-id','skus',attributes);//skus额外信息
     	function fixValidation(){
     		$(".attribute").each(function(i){        		
        		if(i>0){
	        		fixFormValidaton('form-id','attribute',i-1,'attribute_name');
        		}     		
	        });
        	
        	
        	//表内容
        	var trData = "";        	
        	var valueArray = new Array();//规格值二位数组
        	$(".specnvalue-box").each(function(k){  
        		if(k>0){
	        		valueArray[k] = new Array();
	        		$($(this).children(".specvaluelabel")).each(function(){
	        			valueArray[k].push($(this).text());
	        		});   
        		}    			
        	});      	
        	        	
        	var tdnext = "";   
        	var dataArr=valueArray;  //去掉第一个空的数组
		    dataArr.shift();
		    var rs=rank(dataArr); //排列出所有可能的组合
        	
        	if(typeof(rs)!='undefined'){
	        	if(rs[0] instanceof Array){
					for(var i=0;i<rs.length;i++){	        		
			        	for(var j=0;j<rs[i].length;j++){
			        		fixMultipleFormValidaton('form-id','attributeValue',i,j,'value_str');        		
			        	}
			        	fixFormValidaton('form-id','skus',i,'sale_price');
			        	fixFormValidaton('form-id','skus',i,'stock');	        	
		        	}
				}else{
					for(var i=0;i<rs.length;i++){ 
		        		fixMultipleFormValidaton('form-id','attributeValue',i,0,'value_str');     	
						fixFormValidaton('form-id','skus',i,'sale_price');
			        	fixFormValidaton('form-id','skus',i,'stock');
					}				
				}
        	}
        }
     };
     <?php $this->endBlock() ?>  
	<?php $this->registerJs($this->blocks['initSkus'], \yii\web\View::POS_END); ?>
</div>
