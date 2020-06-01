<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年12月25日 下午3:42:38
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;
?>
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
        <div class="main" style="margin-top:.88rem;">
            <div class="withdraw-deposit-bd">
                <div class="withdraw-deposit-bd-content">
                    <p class="fs28">提现金额</p>
                    <input type="text" name="" value="￥">
                    <p class="fs28 mgt20">余额￥<?=$member['user_money']?$member['user_money']:0.00?>，<a href="#">全部提现</a></p>
                    <div class="weui-btn-area">
                        <a class="weui-btn weui-btn_primary fs28" href="javascript:" id="showTooltips">确认提现</a>
                    </div>
                </div>
            </div>
        </div>