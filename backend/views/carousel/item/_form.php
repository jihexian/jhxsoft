<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CarouselItem */
/* @var $form yii\bootstrap\ActiveForm */
?>


<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-6">
        <?php echo $form->errorSummary($model) ?>
        <?php echo $form->field($model, 'image')->widget(\common\modules\attachment\widgets\SingleWidget::className(), ['onlyUrl' => true,'thumb'=>1,'width'=>640,'height'=>360]) ?>
       <!-- <p class="help-block help-block-error" style="font-size: 12px;color: #ff4444;">图片上传尺寸建议为640*360像素</p>-->

    </div>
    <div class="col-md-6">

        <?php echo $form->field($model, 'url')->textarea(['maxlength' => 1024]) ?>

        <?php echo $form->field($model, 'caption')->textarea() ?>

        <?php echo $form->field($model, 'sort')->textInput() ?>

        <?php echo $form->field($model, 'status')->checkbox() ?>

        <div class="form-group">
            <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
