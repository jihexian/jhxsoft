<?php
use yii\helpers\Url;
use common\widgets\sms\SmsWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<header class="top-fixed">
	<div class="weui-flex top-box">
		<div onclick="javascript:history.back(-1);">
			<i class="iconfont icon-fanhui"></i>
		</div>
		<div class="weui-flex__item">店铺申请</div>
		<div>
			<i class="iconfont icon-mulu" id="mulu-bt"></i>
		</div>
	</div>
</header>
<div class="mgt68 apply-shop">
<?php $form = ActiveForm::begin(['id' => 'apply']); ?>
    	<div class="weui-cells weui-cells_form">
            <div class="weui-cell" style="margin-top: 6px;">
    			<div class="weui-cell__hd">
    				<label class="weui-label"><em class="crf4 mgr10">*</em>店铺名</label>
    			</div>
    			<div class="weui-cell__bd">
    				 <?= $form->field($model, 'name', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['maxlength' => true,'class'=>'weui-input']) ?>
    			</div>
		   </div>
        <div class="weui-cell">
        			<div class="weui-cell__hd">
        				<label class="weui-label"><em class="crf4 mgr10">*</em>店铺地址</label>
        			</div>
        			<div class="weui-cell__bd">
        					 <?= $form->field($model, 'address', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['maxlength' => true,'class'=>'weui-input']) ?>
        			</div>
        </div>
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label"><em class="crf4 mgr10">*</em>电话号码</label>
			</div>
			<div class="weui-cell__bd">
				<?= $form
				->field($model, 'mobile', ['template' => '{input}{error}','options' => ['tag=>false']])->textInput(['maxlength' => true,'class'=>'weui-input','placeholder' => '请输入手机号'])?>
			</div>
		</div>
		<div class="weui-cell">
			<div class="weui-cell__hd">
				<label class="weui-label"><em class="crf4 mgr10">*</em>验证码</label>
			</div>
			<div class="weui-cell__bd">
				
			</div>
		</div>
	</div>
	<div class="weui-cells">
		<div class="weui-cell">
			<div class="weui-cell__bd">
				<div class="weui-uploader">
					<div class="weui-uploader__hd">
						<p class="weui-uploader__title">
							<em class="crf4 mgr10">*</em>请上传营业执照：
						</p>
					</div>
					<div class="weui-uploader__bd">
							<?php echo $form->field($model, 'license')->widget(\common\modules\attachment\widgets\MultipleWidget::className(), ['onlyUrl' => true])->label(false) ?>
					
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="weui-cells">
		<div class="weui-cell">
			<div class="weui-uploader">
				<div class="weui-uploader__hd">
					<p class="weui-uploader__title">
						<em class="crf4 mgr10">*</em>请上传身份证正反面：
					</p>
				</div>
				<div class="weui-uploader__bd">
						<?php echo $form->field($model, 'idcard')->widget(\common\modules\attachment\widgets\MultipleWidget::className(), ['onlyUrl' => true])->label(false) ?>
				</div>
			</div>
		</div>
	</div>

      <div class="weui-btn-area">
            <?= Html::submitButton('提交', ['class'=>'weui-btn weui-btn_primary fs32','name' =>'submit-button']) ?>
     </div>
   </div>
<?php 
ActiveForm::end(); 
?>

<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>  