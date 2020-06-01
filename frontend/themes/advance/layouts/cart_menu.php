<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com  pengpeng 617191460@qq.com
 * Time:2018年11月16日 上午10:11:55
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;

?>
     <nav class="weui-tabbar mulu-con" id="mulu-con" style="height:55px;top:.8rem;display: none">

            <a href="<?=Url::to(['site/index'])?>" class="weui-tabbar__item">
                    <i class="iconfont icon-shouye"></i>
                    <p class="weui-tabbar__label">首页</p>
            </a>
            <a href="<?=Url::to(['product-type/index'])?>" class="weui-tabbar__item">

                    <i class="iconfont icon-leimupinleifenleileibie"></i>
                    <p class="weui-tabbar__label">分类</p>
            </a>
            <a href="<?=Url::to(['cart/index'])?>" class="weui-tabbar__item">
                     <i class="iconfont icon-gouwu"></i>
                    <p class="weui-tabbar__label">购物车</p>
            </a>
            <a href="<?=Url::to(['member/index'])?>" class="weui-tabbar__item">

                    <i class="iconfont icon-renwu"></i>
                    <p class="weui-tabbar__label">我的</p>
            </a>
        </nav>
