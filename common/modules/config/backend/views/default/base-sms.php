<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 */

$this->title = '短信基础配置';
?>
<?php $form = ActiveForm::begin([
    'id' => 'sms-setting-form',
]); ?>

    <?=$form->field($model, 'accessKeyId')->textInput(['autocomplete' => 'off'])?>

    <?=$form->field($model, 'accessKeySecret')->textInput(['autocomplete' => 'off'])?>
    

	<?=$form->field($model, 'mobile')->textInput(['autocomplete' => 'off'])?>

    <?= Html::submitButton(Yii::t('app', '保存'), ['class' => 'btn bg-maroon btn-flat btn-block'])?>


<?php $form::end(); ?>