<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ProductCategory;
use common\helpers\Tree;
use common\models\CategoryModel;

/* @var $this yii\web\View */
/* @var $model common\models\ProductCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'parent_id')->dropDownList(ProductCategory::getDropDownList(Tree::build(ProductCategory::lists(),'category_id','parent_id')), ['prompt' => '请选择','options' => [$model['category_id'] => ['disabled' => true]]]) ?>
    <?= $form->field($model, 'cat_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'sort')->textInput() ?>
	<?php echo $form->field($model, 'image')->widget(\common\modules\attachment\widgets\SingleWidget::className(), ['onlyUrl' => true,'thumb'=>1,'width'=>320,'height'=>320]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
