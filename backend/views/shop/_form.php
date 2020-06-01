<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\helpers\Tree;
use common\models\Village;
use common\models\ShopCategory;

use common\assets\LayerAsset;
LayerAsset::register($this);
$action=$this->context->action->id;
?>
<style>
.padding0{
   padding:0px;
}
</style>
<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?=$form->field($model, 'category_id')->dropDownList(ShopCategory::getDropDownList())?>
    <?=$form->field($model, 'tel')->textInput(['maxlength' => true])?>
    <div class="form-group">
 
                    <label class="col-xs-12 padding0">地图定位：</label>
                    <div class="col-xs-12 padding0">
                        <div>
                             <?= $form->field($model, 'map', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['maxlength' => true,'class'=>'form-control width200']) ?>
                        </div>   
                            <a onclick="selectMap()" class="ncap-btn"><i class="fa fa-search"></i>选择位置坐标</a>                 
                        <small style=""></small>
                    </div>     
    </div>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?=$form->field($model, 'business_hours')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'logo')->widget(\common\modules\attachment\widgets\SingleWidget::className(), ['onlyUrl' => true]) ?>

	<?php
       if($action!='create'){
       echo $form->field($model, 'image')->widget(\common\modules\attachment\widgets\SingleWidget::className(), ['onlyUrl' => true]);
	   echo $form->field($model, 'license')->widget(\common\modules\attachment\widgets\SingleWidget::className(), ['onlyUrl' => true]);
       echo $form->field($model, 'vrlink')->textInput(['maxlength' => true]);
       echo  $form->field($model, 'sort')->textInput(['maxlength' => true]);
       }
        ?>
 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '保存' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>

  <?php $this->beginBlock('shop') ?>  
     
    function selectMap(){
        var url = "/admin/product/map";
        layer.open({
            type: 2,
            title: '选择位置',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: url,
        });
    }
    function call_back(poi,addr){
        $('#shop-map').val(poi);
        $('#shop-address').val(addr);
        layer.closeAll('iframe');
       
    }
    <?php $this->endBlock() ?>  
    <?php $this->registerJs($this->blocks['shop'], \yii\web\View::POS_END); ?>