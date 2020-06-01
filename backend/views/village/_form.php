<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\base\Model;
use common\models\Region;
/* @var $this yii\web\View */
/* @var $model common\models\village */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>
<!--       <div class="form-group field-village-contact required"> -->
<!-- 	<label class="control-label" for="village-contact">帮扶地区</label> -->
    <?php // $form->field($model, 'district_id',['template' => '{input}{error}','options' => ['tag=>false','class'=>'form-inline' ]])->widget(\chenkby\region\Region::className(),[
//         'model'=>$model,
//         'url'=> \yii\helpers\Url::toRoute(['get-region']),
//         'province'=>[
//             'attribute'=>'province_id',
//             'items'=>Region::getRegion(),
//             'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择省份']
//         ],
//         'city'=>[
//             'attribute'=>'city_id',
//             'items'=>Region::getRegion($model['province_id']),
//             'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择城市']
//         ],
//         'district'=>[
//             'attribute'=>'district_id',
//             'items'=>Region::getRegion($model['city_id']),
//             'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择县/区']
//         ]
//     ]);
    ?> 
<!--     <div class="help-block"></div></div> -->
	<?= $form->field($model,'province_id')->dropDownList($model->getCityList(0),
    [
        'prompt'=>'--请选择省--',
        'onchange'=>'
            $(".form-group.field-village-district_id").hide();
            $.post("'.yii::$app->urlManager->createUrl('village/site').'?typeid=1&pid="+$(this).val(),function(data){
                $("select#village-city_id").html(data);
            });',
    ]) ?>

	<?= $form->field($model, 'city_id')->dropDownList($model->getCityList($model->province_id),
    [
        'prompt'=>'--请选择市--',
        'onchange'=>'
            $(".form-group.field-village-district_id").show();
            $.post("'.yii::$app->urlManager->createUrl('village/site').'?typeid=2&pid="+$(this).val(),function(data){
                $("select#village-district_id").html(data);
            });',
    ]) ?>
	<?= $form->field($model, 'district_id')->dropDownList($model->getCityList($model->city_id),['prompt'=>'--请选择区--',]) ?>
    <?php // $form->field($model, 'province_id')->textInput() ?>

    <?php // $form->field($model, 'city_id')->textInput() ?>

    <?php // $form->field($model, 'district_id')->textInput() ?>

    <?= $form->field($model, 'money')->textInput() ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?php // $form->field($model, 'status',['template' => '{input}{error}','options' => ['tag=>false' ]])->radioList(array(1=>'有效',0=>'无效'))?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
<?php $this->beginBlock('block1') ?>  

<?php $this->endBlock() ?>  
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>
