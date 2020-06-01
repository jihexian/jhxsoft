<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 */

$this->title = '短信模板配置';
?>
<?php $form = ActiveForm::begin([
    'id' => 'sms-setting-form',
]); ?>

    <?=$form->field($model, 'accessKeyId')->textInput(['autocomplete' => 'off','disabled'=>'disable'])?>

    <?=$form->field($model, 'accessKeySecret')->textInput(['autocomplete' => 'off','disabled'=>'disable'])?>
    
    <?=$form->field($model, 'mobile')->textInput(['autocomplete' => 'off','disabled'=>'disable'])?>

    <?=$form->field($model, 'commonTemplateCode')->textInput(['autocomplete' => 'off'])?>
    
    <?=$form->field($model, 'signName')->textInput(['autocomplete' => 'off'])?>
    
    <?=$form->field($model, 'tempText')->textarea(['autocomplete' => 'off','readonly'=>'readonly'])?>


	

    <?= Html::submitButton(Yii::t('common', '保存'), ['class' => 'btn bg-maroon btn-flat btn-block'])?>


<?php $form::end(); ?>