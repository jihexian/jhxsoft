<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年11月16日 下午5:22:05
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use common\helpers\Util;
use yii\helpers\Url;
?>

       <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    购物车
                </div>
            
                <div style="margin-right: .3rem;"><i class="iconfont icon-mulu" id="mulu-bt"></i></div>
            </div>
        </header>
           <?=$this->render('../layouts/cart_menu')?>
     
         <form id="cart_list" class="mgt68" name="formCart" action="<?=Url::to(['cart/ajax-list'])?>" method="post">
         </form> 

 <?php 
$ajx_list=Url::to(['cart/ajax-list']);
$this->registerJs(<<<JS
$(document).ready(function(){
    ajax_cart_list(); // ajax 请求获取购物车列表
});
JS
);
?>

