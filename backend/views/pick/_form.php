<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\assets\LayerAsset;
use common\logic\RegionLogic;
LayerAsset::register($this);
/* @var $this yii\web\View */
/* @var $model common\models\Pick */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">

    <?php $form = ActiveForm::begin(); ?>
     <?= $form->field($model, 'name')->textarea(['maxlength' => true]) ?>
     <?= $form->field($model,'province_id')->dropDownList(RegionLogic::getRegions(null,1),
    [
        'prompt'=>'--请选择省--',
        'onchange'=>'
        
            $.post("'.yii::$app->urlManager->createUrl('pick/region').'?level=2&parent_id="+$(this).val(),function(data){
                $("select#pick-city_id").html(data);
            });',
    ]) ?>
    <?= $form->field($model,'city_id')->dropDownList(RegionLogic::getRegions(null,$model->province_id),
    [
        'prompt'=>'--请选择市--',
        'onchange'=>'
            $.post("'.yii::$app->urlManager->createUrl('pick/region').'?level=3&parent_id="+$(this).val(),function(data){
                $("select#pick-area_id").html(data);
            });',
    ]) ?>

	<?= $form->field($model, 'area_id')->dropDownList(RegionLogic::getRegions(null,$model->city_id),
    [
        'prompt'=>'--请选择地区--',
    ]) ?>
    
<div class="form-group">
<label class="control-label" for="pick-map">地图定位：</label>

      <div>
        <?= $form->field($model, 'map', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['maxlength' => true,'class'=>'form-control width200']) ?>
       </div>   
        <a onclick="selectMap()" class="ncap-btn"><i class="fa fa-search"></i>选择位置坐标</a>       
</div>

    <?= $form->field($model, 'info')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'master')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->checkbox(['maxlength' => true])?>
 <?= $form->field($model, 'is_free')->checkbox(['maxlength' => true])?>
    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
<?php $this->beginBlock('map') ?>  
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
        $('#pick-map').val(poi);
        $('#pick-info').val(addr);
        layer.closeAll('iframe');
       
    }
 <?php $this->endBlock() ?>  
<?php $this->registerJs($this->blocks['map'], \yii\web\View::POS_END); ?>