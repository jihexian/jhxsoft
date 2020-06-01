<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年12月25日 上午11:59:46
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;

?>
<style type="text/css">
    .weui-btn-area{margin-bottom: .4rem;}
</style>
        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    我的钱包
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
    <?=$this->render('../layouts/cart_menu')?>
        <div class="main">
            <div class="wallet-hd">
                <i class="iconfont icon-yue"></i>
                <p>可用余额</p>
                <em>￥<?=$money?$money:0.00?></em>
            </div>
            <div class="wallet-bd">
                <div class="weui-btn-area">
                    <a class="weui-btn weui-btn_primary fs32" href="<?php echo Url::to(['member/recharge']);?>" id="showTooltips">账户充值</a>
                </div>
                <div class="weui-btn-area">

                    <a class="weui-btn weui-btn_default fs28 cr333" href="<?php echo Url::to(['member/withdrawal'])?>" id="showTooltips">余额提现</a>

                </div>
                <a href="<?=Url::to(['member/money-log'])?>" class="fs28 cr999 mgt20 block">余额明细>></a>
            </div>
        </div>

