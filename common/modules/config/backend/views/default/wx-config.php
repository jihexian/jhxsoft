<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 */

$this->title = '微信公众号配置';
?>
<?php $form = ActiveForm::begin([
    'id' => 'sms-setting-form',
]); ?>

    <?=$form->field($model, 'appid')->textInput(['autocomplete' => 'off'])?>

    <?=$form->field($model, 'appSecret')->textInput(['autocomplete' => 'off'])?>
    <?=$form->field($model, 'token')->textInput(['autocomplete' => 'off'])?>
    <?=$form->field($model, 'encodingAesKey')->textInput(['autocomplete' => 'off'])?>

    <?= Html::submitButton(Yii::t('common', '保存'), ['class' => 'btn bg-maroon btn-flat btn-block'])?>


<?php $form::end(); ?>