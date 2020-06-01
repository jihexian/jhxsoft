<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ProductCategory;
use common\helpers\Tree;
use yii\grid\GridView;
use common\models\CategoryModelAttr;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\CategoryModel */
/* @var $form yii\widgets\ActiveForm */

?>

<style type="text/css">
    body{font-size: 14px}
    em, i {
        font-style: normal;
    }
    textarea {
        resize: none;
    }

    .tr{text-align: right;width: 80%}
    .tc{text-align: center}
    .mgt5{margin-top: 5px;}
    .mgt10{margin-top: 10px;}
    .form-group > label {
    text-align: right;
    font-weight: normal;
    margin-bottom: 0;
    }
    label em {
        color: red;
        margin-right: 2px;
        font-size: 20px
    }
    .form-group label em {
        margin-right: 5px;
        vertical-align: middle;
    }
    .form-control {
        height: 32px;
        padding: 5px 10px;
        border-radius: 2px;
        box-shadow: none;
    }
    .width150{width:150px;}
    .width80{width: 80%;}
    .width60{width: 60%;}
    button{
    	border: none;
    	padding:5px 20px;
        border-radius: 5px;
    	outline: none
    }

    .add,.save{
    	background-color:#3c8dbc;
    	color: #fff;	
    }
    /*.add{padding:5px 10px;background-color: #fff;border:1px solid #e6e6e6;}*/
    .add:hover,.save:hover{background-color:#367fa9;}
    .add:focus,.save:focus{background-color:#286090}
    .cancel{margin-left: 10px;}
    .cancel:hover{background-color: #ccc}
    .cancel:focus{background-color: #949292}
    .tc{text-align: center;}
    .table > tbody > tr > td{/*height: 48px*/line-height: 1; }
    .table .form-control{width: 100%;height:32px;}
    .table input[type="checkbox"]{margin-top: 10px;}
    .tip{margin-bottom: 0;margin-top: 5px;margin-left:5px;color: red;font-size: 12px;}
    .attr-box-item{border-bottom: 1px dashed #e6e6e6;padding-bottom: 10px;margin-bottom: 20px;}
    #add-val-btn{margin-top: 20px;}
    .val-name{margin-bottom: 8px;margin-right: 20px;}
    .dis-in-b{display: inline-block;}
    .attr>div{display: inline-block;}
</style>
<div class="box box-primary">
    <div class="box-body">
    <?php $this->registerJsFile('@web/js/common.js',['depends' => 'backend\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]); ?>
    	<?php $form = ActiveForm::begin(
    		[
    			'id' => 'form-id',
    			'fieldConfig' => [
    				//'template' => "{input}\n<div class=\"col-lg-8\" style=\"padding-left:0\">{error}</div>",    										
    			],  
    			'enableAjaxValidation' => true,
    			//'validationUrl' => Url::to(['category-model/validate-view']),
    			'validateOnChange'=>false,
    			'validateOnBlur'=>false
    		]
    	); ?>   
    	    	 
    	<?php $this->beginBlock('addList') ?>  
    		
		    $(function($) {
		    	 
		    	//添加属性值
		      	$(".attr-box").on("click",".add",function(){
		      	
		      		var rowsize = $('.attr-box-item').index($(this).parents('.attr-box-item')); 
		      		var valuesize = $(this).parent().siblings('.grid-view').find('tr').size()-1;	
		      			
		      		//console.log('valuesize'+valuesize);      		 		
		      		var value_str = '<tr data='+valuesize+'><td>'+'<?php echo str_replace("\n", '', trim($form->field($modelAttrValue, "[rowsize][valuesize]value_str",['template' => '{input}{error}','options' => ['tag=>false' ]])->textInput(['class'=>'form-control']))) ?>'+'</td></td>';
		      		var sort = '<td>'+'<?php echo str_replace("\n", '', trim($form->field($modelAttrValue, "[rowsize][valuesize]sort",['template' => '{input}{error}','options' => ['tag=>false' ]])->textInput(['class'=>'form-control']))) ?>'+'</td></td>';		      		
		      		var status = '<td class="tc">'+'<?php echo str_replace("\n", '',trim($form->field($modelAttrValue, "[rowsize][valuesize]status", ['template' => '{input}','options' => ['tag=>false']])->checkbox())) ?>'+'</td>';		      				      		
					var list = value_str;
					list += sort;
					list += status;									
					list += '<td class="tc"><a class="btn btn-default btn-xs mgt5 move-btn" href="#"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
					list = list.replace(/rowsize/g,rowsize);	
					list = list.replace(/valuesize/g,valuesize);											
					$(this).parent().prev().find('.list').append(list);	
					console.log('valuesize'+valuesize);   
					var attributes = new Array('value_str','sort','status');
	                //激活activeForm验证js
	                fixMultipleFormValidatons('form-id','categorymodelattrvalue',rowsize,valuesize,attributes);
					
			  	})
			  	
		    	$(".attr-box").on("click",".move-btn",function(){
		    		var len = $(this).parents(".list").find('tr').size();
		    		var rowsize = $('.list').index($(this).parents(".list"));
		            var index = $(this).parents("tr").index();
		            var attributes = new Array('value_str','sort','status','model_attr_value_id');
		            for(var i=index+1;i<len;i++){		             	
		            	renameMultipleValidations('categorymodelattrvalue',rowsize,i,attributes,2); 
		            }            
		        	$(this).parents("tr").remove();
		    	})

                $("#add-val-btn").click(function(){
                	var attrsize = $(".attr-box-item").size()-1;//属性数量   	
                    var str = '<div class="attr-box-item"><div class="form-group">';
                        str += '<label class="col-xs-2">属性：</label>';
                        <?php if (!empty($model->model_id)):?>
                         str += '<?php echo str_replace("\n", '', trim(Html::activeHiddenInput($modelAttr, "[rowsize]model_id",array('value'=>$model->model_id))));?>';
                        <?php endif?>  
                        str += '<div class="col-xs-10"><div class="attr">'+'<?php echo str_replace("\n", '', trim($form->field($modelAttr, '[rowsize]attr_name',['template' => "{input}\n{error}",'options'=>['tag=>false']])->textInput(['maxlength' => true,'class'=>'val-name width150 dis-in-b']))) ?>'
                       		   +'<a id="remove_attr" class="btn btn-default btn-xs mgt5 move-btn" href="#"><span class="glyphicon glyphicon-trash"></span></a></div>';
                        str += '<div id="article-grid" class="grid-view">';
                        str += '<table class="table table-bordered table-hover table-responsive width80">';
                        str += '<thead><tr><th width="150px">属性值</th>';
                        str +=  "<th>排序</th>";
                        str += '<th width="150px">是否启用</th><th width="100px">操作</th></tr></thead>';
                        str += '<tbody class="list"></tbody>';
                        str += '</table></div><div class="tr width80">';
                        str += '<?= Html::button('添加属性值', ['class' => 'add','id'=>'add']) ?></div></div></div></div>';
                        str = str.replace(/rowsize/g,attrsize+1);	 
                        $(".attr-box").append(str);
                        var attributes = new Array('attr_name');
                        attrsize++;   
                        fixFormValidatons('form-id','categorymodelattr',attrsize,attributes);
                                             
                })

                //删除整个属性组
                $(".attr-box").on("click","#remove_attr",function(){    
	                var len = $('.attr-box-item').length;
		            var index = $(this).parents(".attr-box-item").index();
		            var attrAttributes = new Array('attr_name','model_attr_id','model_id');
		            for(var i=index+1;i<len;i++){
		            	renameValidations('categorymodelattr',i,attrAttributes); 
		            }		            
		            var len = $('.list').length;
		            //var index = $(".list").index();		            
		            console.log(index);
		            var attrValuesAttributes = new Array('value_str','sort','status','model_attr_id','model_attr_value_id');
		            if(index!=len-1){
			            for(var i=index+1;i<len;i++){
			            	console.log('i:'+i);
			            	var valuesCount = $(".attr-box-item").eq(i).find(".list").find("tr").size();
			            	for(var j = 0;j<valuesCount;j++){
			            	console.log('j:'+j);
			            		renameMultipleValidations('categorymodelattrvalue',i,j,attrValuesAttributes,1); 
			            	}			            	
			            }
		            }	                      	
                    $(this).parents(".attr-box-item").remove();                    
                })
		    }); 
		<?php $this->endBlock() ?>  
		<?php $this->registerJs($this->blocks['addList'], \yii\web\View::POS_END); ?>  
    	<div class="tab-pane">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-xs-2"><em>*</em>模型名称：</label>
                    <div class="col-xs-10">
                        <div>
                        	<?= $form->field($model, 'model_name',['template' => "{input}\n{error}",'options'=>['tag=>false']])->textInput(['maxlength' => true,'class'=>'val-name width150 dis-in-b'])?>
                        </div>                    
                    </div>     
                </div>
                <div class="attr-box">
                <?php foreach ($model->categoryModelAttr as $i => $attr): ?>   
                    <div class="attr-box-item">
                      <div class="form-group">
                        <label class="col-xs-2">属性：</label> 
                        <div class="col-xs-10">
                        	<?php echo Html::activeHiddenInput($attr, "[{$i}]model_id");?>
                        	<?php echo Html::activeHiddenInput($attr, "[{$i}]model_attr_id");?>
                            <div class="attr"><?= $form->field($attr, "[{$i}]attr_name",['template' => "{input}\n{error}",'options'=>['tag=>false']])->textInput(['maxlength' => true,'class'=>'val-name width150 dis-in-b'])?> 
                                <a id="remove_attr" class="btn btn-default btn-xs mgt5 move-btn" href="#"><span class="glyphicon glyphicon-trash"></span></a>                          
                            <select class="form-control width150 dis-in-b" style="display: none"></select>
                            </div>
                            <div id="article-grid" class="grid-view">
                                <table class="table table-bordered table-hover table-responsive width80">
                                    <thead>
                                        <tr><th width="150px">属性值</th><th>排序</th><th width="150px">是否启用</th><th width="100px">操作</th></tr>
                                    </thead>
                                    <tbody class="list">
                                    <?php foreach ($attr->categoryModelAttrValue as $j => $attrValue): ?>                                
                                    <tr data='<?php echo $j;?>'>                                    
                                        <?php echo Html::activeHiddenInput($attrValue, "[{$i}][{$j}]model_attr_value_id");?>
                                        <td><?= $form->field($attrValue, "[{$i}][{$j}]value_str", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control'])?></td>
                                    <td><?= $form->field($attrValue, "[{$i}][{$j}]sort", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control'])?></td>
                                        <td class="tc"><?= $form->field($attrValue, "[{$i}][{$j}]status", ['template' => '{input}','options' => ['tag=>false']])->checkbox()?></td>
                                        <td class="tc"><a class="btn btn-default btn-xs mgt5 move-btn" href="#"><span class="glyphicon glyphicon-trash"></span></a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>  
                            </div>
                            <div class="tr width80"><?= Html::button('添加属性值', ['class' => 'add','id'=>'add']) ?><div>
                        </div>
                      </div> 
                    </div>
                </div>                  
            </div> 
            <?php endforeach; ?>                  
        </div>
        <div class="form-group tc"><a class="btn btn-primary btn-flat btn-l" href="javascript:;" id="add-val-btn">添加属性</a></div>
        <div class="form-group" style="margin-left: 40px;">
            <?= Html::submitButton($model->isNewRecord ? '保存' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
        </div> 
        <?php ActiveForm::end(); ?>
    </div>
</div>

