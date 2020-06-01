<?php
use common\models\Plugin;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>

      <style type="text/css">
        .money{width: 90%;margin: 0 auto;padding: 20px 0;}
        .money_input{display: flex;font-size: 30px;margin-top: 10px;border-bottom: 1px solid #eee}
        .icon-zhifubaozhifu1{color: #23b3fd;font-size: .4rem;}
      </style>

       <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    账户充值
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
       <?=$this->render('../layouts/cart_menu')?>
       
        <div class="main"> 
           <?php 
       $form = ActiveForm::begin([
        'id' => 'recharge',
        'options' => ['class' => 'form-horizontal'],
       ])?>
          <div class="bgfff">
            <div class="money">
              <div style="color:#999;">充值金额</div>
              <div class="money_input">
                <div>￥</div>
                <div>
                  <?= $form->field($model, 'pay_amount',[  
                      'template' => "<div  class='weui-cell__bd'>{input}</div>\n<div class=\"col-lg-5\">{error}</div>",  
                  ])->textInput(['maxlength' => 255,'placeholder'=>'','type'=>'text','class'=>'weui-input'])?>
                </div>
              </div>
            </div>

          </div>            

              <?= $form->field($model, 'payment_code',[
               'options'=>['class' => 'weui-cells_radio pay-cell'],
                'template' => '<div class="weui-cells ">{input}</div>{hint}{error}',
                'labelOptions' => ['class' => 'weui-cells__title'],  //修改label的样式
            ])->radioList($payment,
            [
                'item' => function($index, $label,$name, $checked, $value) {
                  if($label['id'] == 'alipayMobile')
                    $icon = '<span class="iconfont icon-zhifubaozhifu1 mgl20 mgr20"></span>';
                  else
                    $icon = '<span class="iconfont icon-weixinzhifu mgl20 mgr20"></span>';

                  $checked=$index==0?'checked="checked"':"";
                  $return='  <label class="weui-cell weui-check__label" for="x'.$index.'">

                  <div class="weui-cell__bd">
                   <p class="fs28">'.$icon.$value.'</p>
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

     </div>
 
        

