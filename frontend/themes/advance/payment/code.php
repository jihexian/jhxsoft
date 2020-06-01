<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2019年1月28日 下午3:56:21
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;
use  yii\helpers\Html;
?>
	

<div class="payment">
            <!-- 微信支付 -->
            <div class="pay-weixin">
                <div class="p-w-hd">微信支付</div>
                <div class="p-w-bd" style="position:relative"> 
                    <div class="p-w-box">
                        <div class="pw-box-hd">
                            <img id="weixinImageUrl" src="<?=Url::to(['payment/qrcode','url'=>$url,'payment_code'=>$payment_code])?>" width="200" height="200">
                        </div>
                      
                        <div class="pw-box-ft">
                            <p>请使用微信扫一扫</p>
                            <p>扫描二维码支付</p>
                        </div>
                    </div>
                    <div class="p-w-sidebar"></div>
                </div>
            </div>
            <!-- 微信支付 end -->
            <!-- payment-change 变更支付方式 -->
            <div class="payment-change">
                <a class="pc-wrap" onclick="window.history.go(-1)">
                    <i class="pc-w-arrow-left">&lt;</i>
                    <strong>选择其他支付方式</strong>
                </a>
            </div>
            <!-- payment-change 变更支付方式 end -->
</div>
 <?php $this->beginBlock('check') ?> 
    var order_id ="<?=$order_id?>";
    var parent_sn ="<?=$parent_sn?>";

    function pay_status(){
     $.ajax({   
        url:'/order/check-status',
        dataType:'json', 
        type:'post',   
        data:{'order_id':order_id,'parent_sn':parent_sn},  
        success:function(data){   
            if(data.status ==1 ){
                window.clearInterval(int); //销毁定时器
                setTimeout(function(){
                    //跳转到结果页面，并传递状态
                    window.location.href="/order/all";
                },1000)
                
            }
        }, 
        error:function(){   
            $.alert("error");
            
        },   

  });
}
//启动定时器
var int=self.setInterval(function(){pay_status()},5000);

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['check'], \yii\web\View::POS_END); ?>  