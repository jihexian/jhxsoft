<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Shipping */
/* @var $form yii\widgets\ActiveForm */
?>
<?=Html::cssFile('@web/css/diy.css')?>
<?=Html::cssFile('@web/css/area.css')?>
<?=Html::cssFile('@web/css/zTreeStyle.css')?>
<?php $this->registerJsFile('@web/js/region.js',['depends' => 'backend\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('@web/js/common.js',['depends' => 'backend\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('@web/js/jquery.ztree.core.min.js',['depends' => 'backend\assets\AppAsset','position'=>\yii\web\View::POS_END]); ?>
<?php $this->registerJsFile('@web/js/jquery.ztree.excheck.min.js',['depends' => 'backend\assets\AppAsset','position'=>\yii\web\View::POS_END]); ?>
<?php $this->registerJsFile('@web/js/jquery.ztree.exedit.min.js',['depends' => 'backend\assets\AppAsset','position'=>\yii\web\View::POS_END]); ?>
<?php $form = ActiveForm::begin(
    		[
    			'id' => 'form-id', 
    			'enableAjaxValidation' => true,
    			'validateOnChange'=>false,
    			'validateOnBlur'=>false,
    			'validateOnSubmit' => true
    		]); ?>
<?php $this->beginBlock('shipping') ?> 
  //初始化specifyRegions  
  function  initSpecifyRegions(){
	  var specifyRegions = new Array();   
	  $.each($('tr.RegionItem'),function(){
	   	var regions_str = $(this).find('input').eq(1).val().toString();   	
	   	if(regions_str.length>0){
	   		regions_str = regions_str.substr(0,regions_str.length-1);
		   	var regions = regions_str.split(',');   	
		   	//console.log(regions);
		   	$.each(regions,function(){   		
		   		if($.inArray(this,specifyRegions)==-1){
		   			specifyRegions.push(parseInt(this));
		   		}
		   	});
	   	}
	   	
	  }); 
	  return specifyRegions;
  }
  function  initFreeRegions(){
	  var freeRegions = new Array();   
	  $.each($('tr.FreeRegion'),function(){
	   	var regions_str = $(this).find('input').eq(1).val().toString();   	
	   	if(regions_str.length>0){
	   		regions_str = regions_str.substr(0,regions_str.length-1);
		   	var regions = regions_str.split(',');   	
		   	$.each(regions,function(){   		
		   		if($.inArray(this,freeRegions)==-1){
		   			freeRegions.push(parseInt(this));
		   		}
		   	});
	   	}
	   	
	  }); 
	  return freeRegions;
  }
  $(function(){
  		var setting = {
            check: {
                enable: true,
                //autoCheckTrigger: true
            },
            data: {
                simpleData: {
                    enable: true,
                   idKey: "id",
				pIdKey: "pId",
                }
            },
            callback: {
				onCheck: myOnClick
			}
           
        };
        var setting2 = {
            edit: {
                enable: false,
                showRenameBtn: false
            },
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
					pIdKey: "pId",
                }
            },

        };
  		/**
        $("#express,#ems,#sf,#mail").change(function(){
            if($(this).is(":checked")){
                $(this).parent().next("div").removeClass("hidde");
            }else{
                $(this).parent().next("div").addClass("hidde");
            }
        })*/
		
      	
      	//指定城市设置运费按钮
        $(".designated-areas").click(function(){  
        	var specifyRegionCount = $("tr.RegionItem").size();      	    	
			specifyRegionCount++;
            var regiontemStr = '<tr class="RegionItem">';
            	var regions_str = '<?php echo str_replace("\n", '', trim($form->field($modelItem, '[rowsize]regions_str', ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput())) ?>';
           		var regions = '<?php echo str_replace("\n", '', trim($form->field($modelItem, '[rowsize]regions', ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput())) ?>';
                regiontemStr += '<td><a href="javascript:void(0)" class="exit-area" type="1" data="'+specifyRegionCount+'">编辑</a><div class="area-group"><p id="area'+specifyRegionCount+'">未添加地区</p>'+regions_str+regions+'</div></td>';
                var start_num = '<?php echo str_replace("\n", '', trim($form->field($modelItem, '[rowsize]start_num', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']))) ?>';
                regiontemStr += '<td>'+start_num+'</td>';
                var start_price = '<?php echo str_replace("\n", '', trim($form->field($modelItem, '[rowsize]start_price', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']))) ?>';
                regiontemStr += '<td>'+start_price+'</td>';
                var add_num = '<?php echo str_replace("\n", '', trim($form->field($modelItem, '[rowsize]add_num', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']))) ?>';
                regiontemStr += '<td>'+add_num+'</td>'
                var add_price = '<?php echo str_replace("\n", '', trim($form->field($modelItem, '[rowsize]add_price', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']))) ?>';
                regiontemStr += '<td>'+add_price+'</td>'
                regiontemStr += '<td><a class="delete" href="javascript:void(0)">删除</a></td></tr>';
               	regiontemStr = regiontemStr.replace(/rowsize/g,specifyRegionCount);            
				
                if($('.tbl-except thead').is(":hidden")){
                    $('.tbl-except thead').show();
                }
                $(this).parent(".tbl-attach").prev(".tbl-except").find("tbody").last().append(regiontemStr);
                 
                var attributes = new Array('regions','start_num','start_price','add_num','add_price');
                //激活activeForm验证js
                fixFormValidatons('form-id','shippingspecifyregionitem',specifyRegionCount,attributes);    

        });        
        //删除地区item
        $('.tbl-except').on('click','.delete',function(){
       		var len = $('.RegionItem').length;
            var index = $(this).parents(".RegionItem").index();
            console.log(index);
            var attributes = new Array('regions','start_num','start_price','add_num','add_price');
            for(var i=index+1;i<=len;i++){
            	renameValidations('shippingspecifyregionitem',i,attributes); 
            }            
       		$(this).parents('.RegionItem').remove();
        }); 
        //切换包邮条件
        $(".specified-dis").on("change",".setfreeshipping",function(){
         	//console.log($(this).val());
         	var id = $(this).attr('id').split("-")[1];
         	console.log($(this).val());
         	var str1 = '<div class="col-2"><span>满</span><div class="field-shippingfree-rowsize-free_count" 0="tag=>false"><div><input type="text" id="shippingfree-rowsize-free_count" class="form-control" name="ShippingFree[rowsize][free_count]" style="height:30px;"></div><div class="help-block"></div></div><span>件</span></div>';
         	var str2 ='<div class="col-3"><span>满</span><div class="field-shippingfree-rowsize-free_amount" 0="tag=>false"><div><input type="text" id="shippingfree-rowsize-free_amount" class="form-control" name="ShippingFree[rowsize][free_amount]" style="height:30px;"></div><div class="help-block"></div></div><span>元</span></div>';
         	var str3 ='<div class="col-2"><span>满</span><div class="field-shippingfree-rowsize-free_count" 0="tag=>false"><div><input type="text" id="shippingfree-rowsize-free_amount" class="form-control" name="ShippingFree[rowsize][free_count]" style="height:30px;"></div><div class="help-block"></div></div><span>件</span></div><div class="col-3"><span>满</span><div class="field-shippingfree-rowsize-free_amount" 0="tag=>false"><div><input type="text" id="shippingfree-rowsize-free_amount" class="form-control" name="ShippingFree[rowsize][free_amount]" style="height:30px;"></div><div class="help-block"></div></div><span>元</span></div>';
         	str1 = str1.replace(/rowsize/g,id);  
         	str2 = str2.replace(/rowsize/g,id);  
         	str3 = str3.replace(/rowsize/g,id);  
         	    
         	$(this).closest("tr").find('td').eq(1).find('.col-2').remove();
         	$(this).closest("tr").find('td').eq(1).find('.col-3').remove();
         	if($(this).val()==1){        		
         		$(this).closest("tr").find('td').eq(1).append(str1);         		
         	}else if($(this).val()==2){
         		$(this).closest("tr").find('td').eq(1).append(str2);   
         	}else{
         		$(this).closest("tr").find('td').eq(1).append(str3); 
         	}
         });
        //地址选择框进入
        $(".box-body").on("click",".exit-area",function(){//type为1为非免邮地区。type为2为免邮地区
        	if($(this).attr('type')==1){
        		var lineRegions = initSpecifyRegions();   
        	}else{
        		var lineRegions = initFreeRegions();   
        	}
        	     	  
            $(".area-modal-wrap").removeClass("hidde");           
            var regions = $(this).siblings().eq(0).find('input').eq(1).val();
            regions = regions.substr(0,regions.length-1);
            var regionsId = regions.split(",");//当前编辑的regions id数组     
            //重新生成菜单树      
            $.fn.zTree.destroy("#ltree");
            $.fn.zTree.init($("#ltree"), setting, zNodes);
           	var treeObj = $.fn.zTree.getZTreeObj("ltree");
           	var allNodes =  treeObj.getNodes();   
        	//赋值所有的打过勾的值	
        	$.each(lineRegions,function(){        		
        		 var node = treeObj.getNodeByParam("id",this);
        		 node.checked = true;        		 
        	});
           	var nodes = treeObj.getCheckedNodes();           	
           	var rightNodes = new Array();	
           	$.each(nodes,function(){			   
			    if($.inArray(this.id,regionsId)>=0){//如果是当前编辑的regions
			    	//添加到右边
			    	var newNode = new Array();//右边的node
			    	newNode['id'] = this.id;
				    if(this.pId==null){
				      newNode['pId'] = 0;
				    }else{
				      newNode['pId'] = this.pId;
				    }			  
				    newNode['name'] = this.name;
				    if(this.isParent){
				   		newNode['open'] = true;
				   		this.open=true;
				   		treeObj.expandNode(this,false,true,true);
				    }
				    newNode['target'] = " ";
				    rightNodes.push(newNode);			    
				    if(this.isParent){//处理可折叠node
				    	var checkStatus = this.getCheckStatus();
				      	if(checkStatus.half){//checkStatus.half为true时为下面还有选项可选择
				      		this.checked = true;
				      	}
				    }				    
				    
			    }else{//如果是其他的regions
			    	if(!this.isParent){//不是折叠的node
			    		treeObj.setChkDisabled(this, true);
			    	}
			    }			 
			    treeObj.updateNode(this);
			});
		    //重新处理可折叠的nodes
			$.each(nodes,function(){					
				if($.inArray(this.id,regionsId)==-1){
			    	if(this.isParent){//不是折叠的node
			    		var checkStatus = this.getCheckStatus();
			    		if(checkStatus.half){//checkStatus.half为true时为下面还有选项可选择
				      		this.checked = false;
				      	}else{
				      		//关闭check状态
				      		treeObj.setChkDisabled(this, true);				      		
				      	}				    		
			    	}
			    }
			    treeObj.updateNode(this)
			});	
			$.fn.zTree.init($("#rtree"), setting2, rightNodes);
			//传递参数给保存按钮
			$(".js-modal-save").attr('data', $(this).attr('data'));
			$(".js-modal-save").attr('type', $(this).attr('type'));
			//console.log($('.exit-area').index(this)+1);
        }); 
        
        
        <!-- 关闭地址选择框 -->
        $(".js-modal-close").click(function(){
           $(".area-modal-wrap").addClass("hidde"); 
        })
        
        //地址选择框保存
        $(".js-modal-save").click(function(){
           $(".area-modal-wrap").addClass("hidde"); 
           var areaText =  "";
           var regionsValue ="";
		   var treeObj = $.fn.zTree.getZTreeObj("ltree");            
           var nodes = treeObj.getCheckedNodes(true);//只能获取可选的，即当前选的
           $.each(nodes,function(){
			    var newNode = new Array();
			    newNode['id'] = this.id;
			    if(this.pId==null){
			      newNode['pId'] = 0;
			      areaText += this.name+","; 
			    }else{
			      newNode['pId'] = this.pId;
			    }			  
			    newNode['name'] = this.name;
			    if(this.open){
			      newNode['open'] = this.open;
			    }
			    regionsValue +=this.id+",";			    
			    newNode['target'] = " ";
			});
			var type = $(this).attr('type');
			if(type==1){
				$("#area"+$(this).attr('data')).text(areaText);//保存并赋值显示的地区信息
			    $("#shippingspecifyregionitem-"+$(this).attr('data')+"-regions_str").val(areaText);
			    $("#shippingspecifyregionitem-"+$(this).attr('data')+"-regions").val(regionsValue); //保存valueId 
			}else{
				$("#area-free"+$(this).attr('data')).text(areaText);//保存并赋值显示的地区信息
			    $("#shippingfree-"+$(this).attr('data')+"-regions_str").val(areaText);
			    $("#shippingfree-"+$(this).attr('data')+"-regions").val(regionsValue); //保存valueId 
			}	    
        });
		//ztree setting设置的回调，显示右边树
        function myOnClick(event, treeId, treeNode) {
        	var zNodes2 =new Array();
		    var treeObj = $.fn.zTree.getZTreeObj("ltree");            
            var nodes = treeObj.getCheckedNodes(true);
            $.each(nodes,function(){
			    var newNode = new Array();
			    newNode['id'] = this.id;
			    if(this.pId==null){
			      newNode['pId'] = 0;
			    }else{
			      newNode['pId'] = this.pId;
			    }			  
			    newNode['name'] = this.name;
			    if(this.open){
			      newNode['open'] = this.open;
			    }
			    newNode['target'] = " ";
			    zNodes2.push(newNode); 
			 });	   
        	$.fn.zTree.init($("#rtree"), setting2, zNodes2); 	
		};
        
        $("#HasFree").change(function(){
            if($('#HasFree').is(':checked')){
            	$(".specified-dis thead").show();	
                $(".specified-dis .add").show();            
            }else{
                $(".specified-dis thead").hide();
                $(".specified-dis .add").hide();
                $(".specified-dis tbody tr").remove();     
         }
        });
        
        $(".specified-dis").on('click','.add',function(){ 
        	var freeRegionCount = $("tr.FreeRegion").size(); 
	        var addList = '<tr class="FreeRegion">';
	        var freeRegions = '<?php echo str_replace("\n", '', trim($form->field($modelFree, '[rowsize]regions', ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput())) ?>';
	        var freeRegionsStr = '<?php echo str_replace("\n", '', trim($form->field($modelFree, '[rowsize]regions_str', ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput())) ?>';
	        var freeType = '<?php echo str_replace("\n", '', trim($form->field($modelFree, '[rowsize]free_type', ['template' => '{input}{error}','options' => ['tag=>false']])->dropDownList(['1'=>'件数','2'=>'金额','3'=>'件数+金额'],['class'=>'setfreeshipping form-control','style'=>'width:200px','options'=>[3=>['Selected'=>true]]]))) ?>';
	        var freeAmount = '<?php echo str_replace("\n", '', trim($form->field($modelFree, '[rowsize]free_amount', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['style'=>'height:30px;']))) ?>';
	        var freeCount = '<?php echo str_replace("\n", '', trim($form->field($modelFree, '[rowsize]free_count', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['style'=>'height:30px;']))) ?>';
	        addList +=  '<td><a href="javascript:void(0)" class="exit-area mt10" type="2" data="'+freeRegionCount+'">编辑</a><div class="area-group mt10"><p id="area-free'+freeRegionCount+'">未添加地区</p>'+freeRegionsStr+freeRegions+'</div></td>';	       
	        addList +=  '<td><div class="field-shippingfree-free_type">';
	        addList +=  freeType;
	        addList +=  '</div><div class="col-2"><span>满</span>'+freeCount+'<span>件</span></div><div class="col-3"><span>满</span>'+freeAmount+'<span>元</span></div>';
	        addList +=  '</td>';
	        addList +=  '<td><p class="oper"><a href="javascript:void(0)" class="remove">×</a></p></td></tr>';    
	        addList = addList.replace(/rowsize/g,freeRegionCount);      	      
            $('.freeItems').last().append(addList);             
            var attributes = new Array('regions','free_type','free_amount','free_count');
            //激活activeForm验证js
            console.log(freeRegionCount);
            fixFormValidatons('form-id','shippingfree',freeRegionCount,attributes);             
            //freeRegionCount++;                               
        });
        $(".specified-dis").on('click','.remove',function(){
            var len = $('.FreeRegion').length;
            //console.log($(this).parents(".FreeRegion").index());
            var index = $(this).parents(".FreeRegion").index();
            var attributes = new Array('regions','free_type','free_amount','free_count');  
            for(var i=index+1;i<len;i++){
            	renameValidations('shippingfree',i,attributes); 
            }
            $(this).parents(".FreeRegion").remove(); 
            
        });
        $("input[name='Shipping[is_free]']").change(function(){
        	if($(this).val()==1){
        		$('.clearm').addClass('hidde');
        	}else{
        		$('.clearm').removeClass('hidde');
        	}
        });
        
    }) 


<?php $this->endBlock() ?>  
<?php $this->registerJs($this->blocks['shipping'], \yii\web\View::POS_END); ?>
<div class="box box-primary">
    <div class="box-body">
        <div class="freight-template" id="editFreightTemplateDiv">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-xs-2 control-label">模板名称：</label>
                        <div class="col-xs-3">
                        	<?= $form->field($model, 'name', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control']) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-xs-2 control-label">计价方式：</label>
                        <div class="col-xs-3 setradiowidth" id="valuationmethod">
                        <?= $form->field($model, 'type', ['template' => '{input}{error}','options' => ['tag=>false']])->radioList(['1'=>'按件数','0'=>'按重量 '])?>                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-xs-2 control-label">是否包邮：</label>
                        <div class="col-xs-3 setradiowidth" id="whofreight">
                        <?= $form->field($model, 'is_free', ['template' => '{input}{error}','options' => ['tag=>false']])->radioList(['0'=>'自定义运费 ','1'=>'卖家承担运费'])?>                           
                        </div>
                    </div>
                    <div class="form-group clearm <?php if ($model->is_free==1) echo 'hidde';?>"  id="shippertypeid">
                        <label for="inputEmail3" class="col-xs-2 control-label">运送方式：</label>
                        <div class="col-xs-10 setexit">
                            <p>除指定地区外，其余地区的运费采用“默认运费”</p>
                            <div class="select mt10">
                                <p>
                                    <input type="checkbox" checked="checked" disabled="disabled" key="快递"  id="express">
                                    <label for="express">普通快递</label>
                                </p>
                                <div class="freight-editor mt10" style="width:100% !important;">
                                    <div class="entity">
                                        <div class="default">
                                            <div class="form-inline">
                                             <?php if (isset($modelsItem)&&count($modelsItem)>0):?>                                            
                                             <?php foreach ($modelsItem as $i => $item): ?> 
                                             <?php if ($item->is_default==1):?>
                                             <div class="form-group">
                                            	 	<?= $form->field($item, "[0]item_id", ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput() ?>
                                                    <label>默认运费：</label>
                                                    <?= $form->field($item, "[0]start_num", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']) ?>
                                                    <label>&nbsp;<span name="defaultUnit">件/kg</span>内，</label>
                                                </div>
                                                <div class="form-group">
                                                    <?= $form->field($item, "[0]start_price", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']) ?>
                                                    <label>&nbsp;元；</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>&nbsp;每增加</label>
                                                    <?= $form->field($item, "[0]add_num", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']) ?>
                                                    <label>&nbsp;<span name="defaultUnit">件/kg,</span>&nbsp;</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>&nbsp;增加运费</label>
                                                    <?= $form->field($item, "[0]add_price", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']) ?>
                                                    <label>&nbsp;元</label>
                                                </div>
                                                <div class="form-group" style="display:none">                                                    
                                                    <?= $form->field($item, "[0]is_default", ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput(['value'=>'1'])?>                                                    
                                                </div>
                                                <div class="form-group" style="display:none">
                                                    <?= $form->field($item, "[0]delivery_type_id", ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput(['value'=>'1']) ?>
                                                </div>
                                             <?php endif?>  
                                             <?php endforeach; ?>	      
                                             <?php else:?>
                                                <div class="form-group">
                                                    <label>默认运费：</label>
                                                    <?= $form->field($modelItem, '[0]start_num', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']) ?>
                                                    <label>&nbsp;<span name="defaultUnit">件</span>内，</label>
                                                </div>
                                                <div class="form-group">
                                                    <?= $form->field($modelItem, '[0]start_price', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']) ?>
                                                    <label>&nbsp;元；</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>&nbsp;每增加</label>
                                                    <?= $form->field($modelItem, '[0]add_num', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']) ?>
                                                    <label>&nbsp;<span name="defaultUnit">件</span>&nbsp;</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>&nbsp;增加运费</label>
                                                    <?= $form->field($modelItem, '[0]add_price', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10']) ?>
                                                    <label>&nbsp;元</label>
                                                </div>
                                                <div class="form-group" style="display:none">                                                    
                                                    <?= $form->field($modelItem, '[0]is_default', ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput(['value'=>'1'])?>                                                    
                                                </div>
                                                <div class="form-group" style="display:none">
                                                    <?= $form->field($modelItem, '[0]delivery_type_id', ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput(['value'=>'1']) ?>
                                                </div>
                                           
                                            <?php endif?> 
                                             </div> 
                                        </div>
                                        <div class="tbl-except">
                                            <table class="table table-hover table-bordered">
                                                <thead style=""><tr><th width="260">运送到</th><th>首件(件/kg)</th><th>首费(元)</th><th>续件(件/kg)</th><th>续费(元)</th><th>操作</th></tr></thead>
                                                <tbody>
                                                <?php if (isset($modelsItem)):?>
	                                            <?php foreach ($modelsItem as $i => $item): ?> 
	                                            <?php if ($item->is_default==0):?>
	                                            <tr class="RegionItem">
	                                            	
		                                            <td><a href="javascript:void(0)" type="1" class="exit-area" data="<?php echo $i?>">编辑</a><div class="area-group">
		                                            <p id="area<?php echo $i;?>">
		                                            <?php echo $item->regions_str;?>
		                                            
		                                            <?= $form->field($item, "[{$i}]regions_str", ['template' => '{input}','options' => ['tag=>false']])->hiddenInput() ?>
		                                            <?=$form->field($item, "[{$i}]regions", ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput() ?>
		                                            </p>
		                                            </div>
		                                            </td>
		                                            <td>
		                                            <?=$form->field($item, "[{$i}]start_num", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10'])?>
		                                            </td>
		                                            <td>
		                                            <?=$form->field($item, "[{$i}]start_price", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10'])?>
		                                            </td>
		                                            <td>
		                                            <?=$form->field($item, "[{$i}]add_num", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10'])?>
		                                            </td>
		                                            <td>
		                                            <?=$form->field($item, "[{$i}]add_price", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['class'=>'form-control ml10 mr10'])?>
		                                            <?= $form->field($item, "[{$i}]item_id", ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput() ?>
		                                            </td>
		                                            
		                                            <td><a class="delete" href="javascript:void(0)">删除</a></td>
		                                            
	                                            </tr>  
	                                             <?php endif?>  
	                                            <?php endforeach; ?>	                                           
	                                            <?php endif?>  
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tbl-attach">
                                            <a href="javascript:void(0)" class="designated-areas" data-op="setCityFreight1">为指定地区城市设置运费</a>&nbsp;&nbsp;
                                            <a href="javascript:void(0)" class="batch-operation">批量操作</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="select" ng-show="Usertype==0">
                                <p>
                                    <input type="checkbox" name="ShippingType" key="EMS" value="2" id="ems">
                                    <label for="ems">EMS</label>
                                </p>
                                <div class="freight-editor mt10 hidde" style="width:100% !important;">
                                    <div class="entity">
                                        <div class="default">
                                            <div class="form-inline">
                                                <div class="form-group">
                                                    <label>默认运费：</label>
                                                    <input type="text" class="form-control ml10 mr10" id="defaultFreight1" name="defaultFreight">
                                                    <label>&nbsp;<span name="defaultUnit">件</span>内，</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control ml10 mr10" id="money1">
                                                    <label>&nbsp;元；</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>&nbsp;每增加</label>
                                                    <input type="text" class="form-control ml10 mr10" id="addNum1">
                                                    <label>&nbsp;<span name="defaultUnit">件</span>&nbsp;</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>&nbsp;增加运费</label>
                                                    <input type="text" class="form-control ml10 mr10" id="addMoney1">
                                                    <label>&nbsp;元</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tbl-except">
                                            <table class="table table-hover table-bordered">
                                                <thead><tr><th width="260">运送到</th><th>首件(件)</th><th>首费(元)</th><th>续件(件)</th><th>续费(元)</th><th>操作</th></tr></thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="tbl-attach">
                                            <a href="javascript:void(0)" class="designated-areas" data-op="setCityFreight1">为指定地区城市设置运费</a>&nbsp;&nbsp;
                                            <a href="javascript:void(0)" class="batch-operation">批量操作</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="select" ng-show="Usertype==0">
                                <p>
                                    <input type="checkbox" name="ShippingType" key="顺丰" value="3" id="sf">
                                    <label for="sf">顺丰</label>
                                </p>
                                <div class="freight-editor mt10 hidde" style="width:100% !important;">
                                    <div class="entity">
                                        <div class="default">
                                            <div class="form-inline">
                                                <div class="form-group">
                                                    <label>默认运费：</label>
                                                    <input type="text" class="form-control ml10 mr10" id="defaultFreight1" name="defaultFreight">
                                                    <label>&nbsp;<span name="defaultUnit">件</span>内，</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control ml10 mr10" id="money1">
                                                    <label>&nbsp;元；</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>&nbsp;每增加</label>
                                                    <input type="text" class="form-control ml10 mr10" id="addNum1">
                                                    <label>&nbsp;<span name="defaultUnit">件</span>&nbsp;</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>&nbsp;增加运费</label>
                                                    <input type="text" class="form-control ml10 mr10" id="addMoney1">
                                                    <label>&nbsp;元</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tbl-except">
                                            <table class="table table-hover table-bordered">
                                                <thead><tr><th width="260">运送到</th><th>首件(件)</th><th>首费(元)</th><th>续件(件)</th><th>续费(元)</th><th>操作</th></tr></thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="tbl-attach">
                                            <a href="javascript:void(0)" class="designated-areas" data-op="setCityFreight1">为指定地区城市设置运费</a>&nbsp;&nbsp;
                                            <a href="javascript:void(0)" class="batch-operation">批量操作</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="select" ng-show="Usertype==0">
                                <p>
                                    <input type="checkbox" name="ShippingType" key="平邮" value="4" id="mail">
                                    <label for="mail">平邮</label>
                                </p>
                                <div class="freight-editor mt10 hidde" style="width:100% !important;">
                                    <div class="entity">
                                        <div class="default">
                                            <div class="form-inline">
                                                <div class="form-group">
                                                    <label>默认运费：</label>
                                                    <input type="text" class="form-control ml10 mr10" id="defaultFreight1" name="defaultFreight">
                                                    <label>&nbsp;<span name="defaultUnit">件</span>内，</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control ml10 mr10" id="money1">
                                                    <label>&nbsp;元；</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>&nbsp;每增加</label>
                                                    <input type="text" class="form-control ml10 mr10" id="addNum1">
                                                    <label>&nbsp;<span name="defaultUnit">件</span>&nbsp;</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>&nbsp;增加运费</label>
                                                    <input type="text" class="form-control ml10 mr10" id="addMoney1">
                                                    <label>&nbsp;元</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tbl-except">
                                            <table class="table table-hover table-bordered">
                                                <thead><tr><th width="260">运送到</th><th>首件(件)</th><th>首费(元)</th><th>续件(件)</th><th>续费(元)</th><th>操作</th></tr></thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="tbl-attach">
                                            <a href="javascript:void(0)" class="designated-areas" data-op="setCityFreight1">为指定地区城市设置运费</a>&nbsp;&nbsp;
                                            <a href="javascript:void(0)" class="batch-operation">批量操作</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="form-group" id="freetypeid">
                        <label class="col-xs-2 control-label">包邮条件：</label>
                        <div class="col-xs-10">
                            <div class="specified-condition">
                                
                                <div class="specified-dis mt10" id="free_add" class="<?php if($model->free_condition!=1) echo 'hidde';?>">
                                    <a href="javascript:void(0)" class="add ">＋<span style="color: #3c8dbc;font-size: 16px;">添加</span></a>
                                    <table class="table table-hover table-bordered">
                                        <thead class="<?php if($model->free_condition!=1) echo 'hidde';?>">
                                            <tr><th width="25%">选择地区</th><th width="65%">设置包邮条件</th><th>操作</th></tr>                                             
                                        </thead>
                                        <tbody class="freeItems">
                                        <?php if (isset($modelsFree)):?>
                                            <?php foreach ($modelsFree as $i => $free): ?> 
                                           
                                            <tr class="FreeRegion">	                                            	
	                                            <td><a href="javascript:void(0)" class="exit-area mt10" data="<?php echo $i?>" type="2">编辑</a><div class="area-group mt10">
	                                            <p id="area-free<?php echo $i;?>">
	                                            <?php echo $free->regions_str;?>	                                            
	                                            <?= $form->field($free, "[{$i}]regions_str", ['template' => '{input}','options' => ['tag=>false']])->hiddenInput() ?>
	                                            <?=$form->field($free, "[{$i}]regions", ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput() ?>
	                                            </p>
	                                            </div>
	                                            </td>
	                                            <td>
	                                            <div class="field-shippingfree-free_type">
	                                            <?=$form->field($free, "[{$i}]free_type", ['template' => '{input}{error}','options' => ['tag=>false']])->dropDownList(['1'=>'件数','2'=>'金额','3'=>'件数+金额'],['class'=>'setfreeshipping form-control','style'=>'width:200px','options'=>[$free->free_type=>['Selected'=>true]]])?>
	                                            </div>
	                                            <?php if ($free->free_type==1):?>
	                                            <div class="col-2"><span>满</span><?=$form->field($free, "[{$i}]free_count", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['style'=>'height:30px;'])?>件</div>
	                                            <?php elseif ($free->free_type==2):?>
	                                            <div class="col-3"><span>满</span><?=$form->field($free, "[{$i}]free_amount", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['style'=>'height:30px;'])?>元</div>
	                                            <?php else:?>
	                                            <div class="col-2"><span>满</span><?=$form->field($free, "[{$i}]free_count", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['style'=>'height:30px;'])?>件</div>
	                                            <div class="col-3"><span>满</span><?=$form->field($free, "[{$i}]free_amount", ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['style'=>'height:30px;'])?>元</div>
	                                            <?php endif?>  
	                                            </td>
	                                            <td><p class="oper"><a href="javascript:void(0)" class="remove">×</a></p></td>	                                            
                                            </tr>
                                            
                                            <?php endforeach; ?>	                                           
	                                    <?php endif?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="area-modal-wrap hidde">
            <div class="modal-mask"></div>
            <div class="area-modal" style="top:97px;">
                <div class="area-modal-head">选择可配送区域</div>
                <div class="area-box">
                    <div class="zTreeDemoBackground left">
                        <p class="title">可选省、市、区</p>
                        <ul id="ltree" class="ztree"></ul>
                    </div>
                    <div class="right">
                       <p class="title">已选省、市、区</p> 
                       <ul id="rtree" class="ztree"></ul>
                    </div>
                </div> 
                <div style="clear: both;"></div>
                <div class="area-modal-foot">
                    <a class="zent-btn zent-btn-primary btn-wide js-modal-save"  href="javascript:void(0)">确定</a>&nbsp;&nbsp;
                    <a class="zent-btn btn-wide js-modal-close">取消</a>
                </div>
            </div>
        </div>

    </div>
     <div class="form-group save-box">
					<?= Html::submitButton($model->isNewRecord ? '保存' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
				</div>
</div>
  <?php ActiveForm::end(); ?>