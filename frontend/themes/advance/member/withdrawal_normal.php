<?php
/**
 *
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2019年6月25日 下午3:42:38
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
?>
    <style type="text/css">
      .null-data{margin-top: 0px; padding: 20px 0;}
    </style>
    <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    余额提现
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
    </header>
     <?=$this->render('../layouts/cart_menu')?>
        <div class="main" >
           <?php 
       $form = ActiveForm::begin([
        'id' => 'withdrwal',
        'options' => ['class' => 'form-horizontal'],
        
       ])?>
         <div class="weui-cells weui-cells_form">
           <div class="weui-cell">
            <p style="color:red;">账号可提现余额为：<?=$member['user_money']?$member['user_money']:0.00?></p>
          </div>
          <div class="weui-cell">
           <div class="weui-cell__hd"><label class="weui-label">提现金额</label></div>
            <div class="weui-cell__bd">
            <?= $form->field($model, 'pay_amount')->textInput(['placeholder'=>'提现金额至少100','class'=>'weui-input '])->label(false)?>
            </div>
          </div>
           <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">银行名称</label></div>
            <div class="weui-cell__bd">
              <?= $form->field($model, 'bank_name')->textInput(['placeholder'=>'如支付宝、农业银行、建设银行等','class'=>'weui-input '])->label(false)?>
           </div>
          </div>
           <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">银行账号</label></div>
            <div class="weui-cell__bd">
               <?= $form->field($model, 'bank_card')->textInput(['placeholder'=>'如支付宝帐号、农业银行帐号等','class'=>'weui-input '])->label(false)?>
           </div>
          </div>
           <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">开户名</label></div>
            <div class="weui-cell__bd">
              <?= $form->field($model, 'realname')->textInput(['placeholder'=>'开户人姓名','class'=>'weui-input '])->label(false)?>
           </div>
          </div>
          <div class="weui-btn-area">
               <?= Html::submitButton('确定', ['class' => 'weui-btn weui-btn_primary fs28']) ?>
            </div>
          </div>
           <?php ActiveForm::end() ?>
            <div class="withdrawal-box bgfff" style="margin-top:.2rem;">
						<div class="ponit-mian">
						    <div class="distribut">
								<div class="distribut-status">
									<p>编号</p>
								</div>
								<div class="distribut-status">
									<p>申请金额</p>
								</div>
								<div class="distribut-status">
									<p>申请时间</p>
								</div>
								<div class="distribut-status">
									<p>状态</p>
								</div>
							</div>
							<?php foreach ($log as $v):?>
							<div class="distribut-items">
								<li>
									<?=$v->id?>
								</li>
								<li>
									<?=$v->pay_amount?>
								</li>
								<li>
									
							    <?=date('Y-m-d',$v->created_at)?>
								</li>
								<li>
									<?=$v->getStatus($v->status);?>
								</li>
							</div>		
							<?php endforeach;?>
						</div>
            <?php if(count($log) == 0): ?>
              <div class="null-data">
                <div>暂无提现记录~</div>
              </div>
            <?php endif; ?>
            </div>
        </div>
        
<?php

$this->registerJs(<<<JS
  var total=$('#total').val();
  $('#check').click(function() {
     $('#money').val(total)
  });

JS
);
?>   