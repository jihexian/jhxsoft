<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ProductType;
use common\helpers\Tree;

/* @var $this yii\web\View */
/* @var $model common\models\ProductType */
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
    .width80{width: 80%;}
    .width60{width: 60%;}
   	.width16{width:16.66666667%;}
    button{
    	border: none;
    	border-radius: 5px;
    	padding:5px 20px;
    	outline: none
    }
    .specificationitem {padding-bottom: 20px;border-bottom: 1px dashed #e6e6e6;margin-bottom: 15px;position: relative;}
	.specifications-box{padding:20px;border:1px solid #e6e6e6;box-sizing: border-box;width: 85%}
    .add,.save{
    	background-color:#3c8dbc;
    	color: #fff;	
    }
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
</style>
<div class="box box-primary">
    <div class="box-body">
    	<?php $form = ActiveForm::begin(); ?>   
    	    	 
    	<?php $this->beginBlock('addList') ?>  
    		
		<?php $this->endBlock() ?>  
		<?php $this->registerJs($this->blocks['addList'], \yii\web\View::POS_END); ?>  
    	<div class="tab-pane">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-xs-2"><em>*</em>类目名称：</label>
                    <div class="col-xs-2">
                        <div>
                        	<?= $form->field($model, 'type_name',['template' => "{input}\n{error}",'options'=>['tag=>false']])->textInput(['maxlength' => true,'class'=>'form-control'])?>
                        </div>                    
                    </div>     
                </div>
                 <div class="form-group">
                    <label class="col-xs-2"><em>*</em>图片：</label>
                    <div class="col-xs-2">
                        <div>
                         	<?= $form->field($model, 'image',['template' => "{input}\n{error}",'options'=>['tag=>false']])->widget(\common\modules\attachment\widgets\SingleWidget::className(), ['onlyUrl' => true,'thumb'=>0]) ?>
                        </div>                    
                    </div>     
                </div>
               
               <div class="form-group">
                    <label class="col-xs-2"><em>*</em>上级类目：</label>
                    <div class="col-xs-2">
                        <div>
                            <?= $form->field($model, 'parent_id',['template' => "{input}\n{error}",'options'=>['tag=>false']])->dropDownList(ProductType::getDropDownList(Tree::build(ProductType::lists(),'type_id','parent_id')), ['prompt' => '请选择','options' => [$model['type_id'] => ['disabled' => true]]]) ?>
                        </div>                    
                    </div>                
                </div> 
                <div class="form-group">
                    <label class="col-xs-2"><em>*</em>排序：</label>
                    <div class="col-xs-2">
                        <div>
                        	<?= $form->field($model, 'sort',['template' => "{input}\n{error}",'options'=>['tag=>false']])->textInput(['maxlength' => true,'class'=>'form-control'])?>
                        </div>                    
                    </div>     
                </div>
                <div class="form-group">
					 <?= Html::submitButton($model->isNewRecord ? '保存' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
				</div>
                <div class="form-group" style="display: none">
                    <label class="col-xs-2"><?= Html::button('添加规格', ['class' => 'add','id'=>'add']) ?></label>
                    <div class="col-xs-10">		               
          				<div class="specifications-box">  
          					<div class="specificationitem">     				
			                    <div>
			                        	规格名称：	<div class="field-producttype-type_name" 0="tag=>false">
												<input type="text" id="producttype-type_name" class="form-control width16"  maxlength="50">
												<div class="help-block"></div>
												</div>
			                    </div>                    
	          					<table class="table table-bordered table-hover table-responsive width80">
	          						<thead>
										<tr><th width="100px">规格值</th><th width="100px">排序</th><th width="100px">系统默认</th><th width="50px">操作</th></tr>
									</thead>
									<tbody class="list">														
									<tr>
										<td><div class="field-categorymodelattr-1-attr_name has-success" 0="tag=>false"><input type="text" id="categorymodelattr-1-attr_name" class="form-control" aria-invalid="false"><div class="help-block"></div></div></td>
										<td><div class="field-categorymodelattr-1-attr_name has-success" 0="tag=>false"><input type="text" id="categorymodelattr-1-attr_name" class="form-control"  aria-invalid="false"><div class="help-block"></div></div></td>									
										<td class="tc"><div class="field-categorymodelattr-1-search" 0="tag=>false"><input type="hidden"  value="0"><label><input type="checkbox" id="categorymodelattr-1-search"  value="1"> </label></div></td>
										<td class="tc"><a class="btn btn-default btn-xs mgt5 move-btn" href="#"><span class="glyphicon glyphicon-trash"></span></a></td>
									</tr>
									</tbody>
								</table> 
								</div> 
							</div>
						<div>
							<div class="form-group">
					        	<?= Html::submitButton($model->isNewRecord ? '保存' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
					    	</div>
						</div>               
                    </div>                
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>


