<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Store */
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
<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'addr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>
     <?= $form->field($model, 'regions')->hiddenInput(['class'=>'areaids'])->label(false) ?>
    <div class="form-group field-store-tel">
<label class="control-label" for="store-tel">配送地区范围</label><br/>
   
    <input  type="hidden" name="" class="area" />
      <a href="javascript:void(0)" class="exit-area" type="1" data="'+specifyRegionCount+'">编辑</a>
<div class="help-block"></div>
</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
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
    <?php ActiveForm::end(); ?>
    </div>
</div>
<?php $this->beginBlock('shipping') ?> 
  //初始化specifyRegions  
  function  initSpecifyRegions(){
      var specifyRegions = new Array();   
        var regions_str = $(".areaids").val();     
        if(regions_str.length>0){
            regions_str = regions_str.substr(0,regions_str.length-1);
            var regions = regions_str.split(',');       
            $.each(regions,function(){          
                if($.inArray(this,specifyRegions)==-1){
                    specifyRegions.push(parseInt(this));
                }
            });
        }
      return specifyRegions;
  }
  $(function(){
        var setting = {
            edit: {
                enable: true,
                showRenameBtn: false
            },
            check: {
                enable: true,
                autoCheckTrigger: true
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
        //地址选择框进入
        
        $(".box-body").on("click",".exit-area",function(){
            if($(this).attr('type')==1){
                var lineRegions = initSpecifyRegions();   
            }
                      
            $(".area-modal-wrap").removeClass("hidde");           
            var regions = $(".area").val();
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
                if(1){
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
                        newNode['open'] = false;
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
                    
                }        
                treeObj.updateNode(this);
            });

            //重新处理可折叠的nodes
            $.each(nodes,function(){                    
                if(1){
                    if(this.isParent){//不是折叠的node
                        var checkStatus = this.getCheckStatus();
                        if(checkStatus.half){//checkStatus.half为true时为下面还有选项可选择
                            //this.checked = false;
                        }else{
                            //关闭check状态
                            //treeObj.setChkDisabled(this, true);                         
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
           console.log(nodes);
           $.each(nodes,function(){
                var newNode = new Array();
                newNode['id'] = this.id;
                if(this.pId==null){
                  newNode['pId'] = 0;
                  areaText += this.name+","; 
                }else{
                  newNode['pId'] = this.pId;
                }             
                regionsValue +=this.id+",";             
                newNode['target'] = " ";
            });
            var type = $(this).attr('type');
            if(type==1){
                $(".area").val(areaText);//保存并赋值显示的地区信息
                $(".areaids").val(regionsValue); //保存valueId 
            }
        });
        //ztree setting设置的回调，显示右边树
        function myOnClick(event, treeId, treeNode) {
            var treeObj = $.fn.zTree.getZTreeObj("ltree");            
            var nodes = treeObj.getCheckedNodes();
            var arr = new Array();
            $.each(nodes,function(){
                var newNode = new Array();
                newNode['id'] = this.id;
                if(this.pId==null){
                  newNode['pId'] = 0;
                }else{
                  newNode['pId'] = this.pId;
                } 
                if(this.open){
                  newNode['open'] = this.open;
                }else{
                  newNode['open'] = false;
                }           
                newNode['name'] = this.name;
                newNode['target'] = " ";                      
                arr.push(newNode); 
             });    
            $.fn.zTree.init($("#rtree"), setting2, arr);    
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
    })
<?php $this->endBlock() ?>  
<?php $this->registerJs($this->blocks['shipping'], \yii\web\View::POS_END); ?>