<?php

use yii\helpers\Url;

?>
    <div class="wrap">
        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    账户安全
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        
        <div class="main" style="margin-top: 5.68rem;">
            <div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary fs28" href="<?php echo  Url::to(['member/reset-password'])?>" id="showTooltips">重置密码</a>
            </div>
			<div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary fs28" href="<?php echo  Url::to(['member/reset-pay-password'])?>" id="showTooltips">重置支付密码</a>
            </div>
        </div>
    </div>
   