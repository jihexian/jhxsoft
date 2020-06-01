<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\logic\RegionLogic;

/* @var $this yii\web\View */
/* @var $model common\models\ProductTheme */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $this->registerJsFile('@web/js/jquery.bigcolorpicker.js',['depends' => 'backend\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]); ?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin([
//     			'id' => 'form-id',    			
//     			'enableAjaxValidation' => true,
     			'validateOnChange'=>false,
     			'validateOnBlur'=>false,
     			'validateOnSubmit' => true
    		]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	<?php echo $form->field($model, 'image')->widget(\common\modules\attachment\widgets\SingleWidget::className(), ['onlyUrl' => true,'width'=>500,'height'=>500]) ?>
   	<?php echo $form->field($model, 'carousels')->widget(\common\modules\attachment\widgets\MultipleWidget::className(), ['onlyUrl' => true,"multiple"=>true,"sortable"=>true,'thumb'=>1,'width'=>500,'height'=>500]) ?>

    <?php // $form->field($model, 'bgim')->widget(\common\modules\attachment\widgets\SingleWidget::className(), ['onlyUrl' => true,'width'=>500,'height'=>500]) ?>
  
     <?= $form->field($model,'province_id')->dropDownList(RegionLogic::getRegions(2297),
    [
        'prompt'=>'--请选择省--',
        'onchange'=>'
            var district_id = \'<option value="">--请选择地区--</option>\';
            $("select#producttheme-district_id").html(district_id);
            var district_id = \'<option value="">--请选择乡镇--</option>\';
            $("select#producttheme-town_id").html(district_id);
            var district_id = \'<option value="">--请选择村点--</option>\';
            $("select#producttheme-village_id").html(district_id);
            $.post("'.yii::$app->urlManager->createUrl('product-theme/site').'?level=2&parent_id="+$(this).val(),function(data){
                $("select#producttheme-city_id").html(data);
            });',
    ]) ?>
    <?= $form->field($model,'city_id')->dropDownList(RegionLogic::getRegions(null,$model->province_id),
    [
        'prompt'=>'--请选择市--',
        'onchange'=>'
            var district_id = \'<option value="">--请选择乡镇--</option>\';
            $("select#producttheme-town_id").html(district_id);
            var district_id = \'<option value="">--请选择村点--</option>\';
            $("select#producttheme-village_id").html(district_id);
            $.post("'.yii::$app->urlManager->createUrl('product-theme/site').'?level=3&parent_id="+$(this).val(),function(data){
                $("select#producttheme-district_id").html(data);
            });',
    ]) ?>

	<?= $form->field($model, 'district_id')->dropDownList(RegionLogic::getRegions(null,$model->city_id),
    [
        'prompt'=>'--请选择地区--',
        'onchange'=>'
            var district_id = \'<option value="">--请选择村点--</option>\';
            $.post("'.yii::$app->urlManager->createUrl('product-theme/site').'?level=4&parent_id="+$(this).val(),function(data){
                $("select#producttheme-town_id").html(data);
            });',
    ]) ?>
    <?= $form->field($model, 'town_id')->dropDownList(RegionLogic::getRegions(null,$model->district_id),
    [
        'prompt'=>'--请选择乡镇--',
        'onchange'=>'
            ///$(".form-group.field-producttheme-town_id").show();
            $.post("'.yii::$app->urlManager->createUrl('product-theme/site').'?level=5&parent_id="+$(this).val(),function(data){
                $("select#producttheme-village_id").html(data);
            });',
    ]) ?>
      <?= $form->field($model, 'village_id')->dropDownList(RegionLogic::getRegions(null,$model->town_id),
    [
        'prompt'=>'--请选择村点--',        
            
    ]) ?>
      

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([0=>'禁用',1=>'启用',2=>'删除']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
<?php $this->beginBlock('block1') ?>  

<?php $this->endBlock() ?>  
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>
