<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com  pengpeng 617191460@qq.com
 * Time:2018年11月16日 上午10:10:19
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;
$controller=$this->context->id;

?>
   <footer class="weui-tabbar">
            <a href="<?= \yii\helpers\Url::to(['site/index'])?>" class="weui-tabbar__item <?php if($controller=='site'):?>weui-bar__item_on <?php endif;?>">
                    <i class="iconfont icon-shouye"></i>
                    <p class="weui-tabbar__label">首页</p>
            </a>
            <a href="<?= \yii\helpers\Url::to(['shop/lists'])?>" class="weui-tabbar__item <?php if($controller=='shop'):?>weui-bar__item_on <?php endif;?>">

                    <i class="iconfont icon-dianmian"></i>
                    <p class="weui-tabbar__label">商家列表</p>
            </a>
            <a href="<?= \yii\helpers\Url::to(['product-type/index'])?>" class="weui-tabbar__item <?php if($controller=='product-type'):?>weui-bar__item_on <?php endif;?>">
                    <i class="iconfont icon-leimupinleifenleileibie"></i>
                    <p class="weui-tabbar__label">分类</p>
            </a>
            <a href="<?= \yii\helpers\Url::to(['cart/index'])?>" class="weui-tabbar__item <?php if($controller=='cart'):?>weui-bar__item_on <?php endif;?>">
                     <i class="iconfont icon-gouwu"></i>
                    <p class="weui-tabbar__label">购物车</p>
            </a>
            <a href="<?= \yii\helpers\Url::to(['member/index'])?>" class="weui-tabbar__item <?php if($controller=='member'):?>weui-bar__item_on <?php endif;?>">
                    <i class="iconfont icon-renwu"></i>
                    <p class="weui-tabbar__label">我的</p>
            </a>
        </footer>