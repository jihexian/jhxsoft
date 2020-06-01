<?php
use common\models\Plugin;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
       <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    到店支付
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
       <?=$this->render('../layouts/cart_menu')?>
       
        <div class="main"> 
        <div class="weui-cells">
         <div class="weui-cell bgfff">
            <p>店铺名称：<?=$shop['name']?></p>
          </div>
        </div>
           <?php 
       $form = ActiveForm::begin([
        'id' => 'recharge',
        'options' => ['class' => 'form-horizontal'],
       ])?>
       
         <div class="weui-cell bgfff">
         
        <?= $form->field($model, 'pay_amount',[  
            'template' => "<div  class='weui-cell__bd'>{input}</div>\n<div class=\"col-lg-5\">{error}</div>",  
        ])->textInput(['maxlength' => 255,'placeholder'=>'支付金额','class'=>'weui-input fs32'])?>
            </div>

              <?= $form->field($model, 'payment_code',[
               'options'=>['class' => 'weui-cells weui-cells_radio pay-cell'],
                'template' => '<div class="weui-cells ">{input}</div>{hint}{error}',
                'labelOptions' => ['class' => 'weui-cells__title'],  //修改label的样式
            ])->radioList($payment,
            [
                'item' => function($index, $label, $name, $checked, $value) {
                $checked=$index==0?'checked="checked"':"";
                    $return='  <label class="weui-cell weui-check__label" for="x'.$index.'">

                <div class="weui-cell__bd">
                 <p class="fs28"><span class="iconfont icon-weixinzhifu mgl20"></span>'.$value.'</p>
                </div>
                <div class="weui-cell__ft">
                  <input type="radio" value="'.$label['id'].'" name="' . $name . '" class="weui-check" id="x'.$index.'" '.$checked.'>
              
                  <span class="weui-icon-checked"></span>
                </div>
              </label>';

                  return $return;
                }
                
            ]) ?>

      
            <div class="weui-btn-area">
               <?= Html::submitButton('确定', ['class' => 'weui-btn weui-btn_primary fs28']) ?>
              
            </div>
        <?php ActiveForm::end() ?>
        
         <div class="withdrawal-box bgfff" style="margin-top:.2rem;">
						<div class="ponit-mian">
						    <div class="distribut">
								<div class="distribut-status">
									<p>编号</p>
								</div>
								<div class="distribut-status">
									<p>支付金额</p>
								</div>
								<div class="distribut-status">
									<p>支付时间</p>
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
									
							    <?=date('Y-m-d',$v->pay_time)?>
								</li>
								<li>
							    <?php echo $v->payment_status==1?'支付成功':'等待支付'?>
								</li>
							</div>		
							<?php endforeach;?>	
						</div>
            </div>
     </div>
 
        

