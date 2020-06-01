<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年11月16日 上午10:08:13
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use common\helpers\Util;
use yii\helpers\Url;
?>
 <div class="wrap">
        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    订单确认
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
         <?= $this->render('../layouts/cart_menu') ?>
        <div class="main" style="margin-top: .88rem;">
            <div class="weui-cells">
                <a class="weui-cell weui-cell_access" href="javascript:;">
                    <div class="iconfont icon-dizhi"></div>
                    <div class="weui-cell__bd">
                        <p class="fs32 cr333">小小春&nbsp;&nbsp;18877705168</p>
                        <p class="fs24 cr999">广西钦州市银河街88号</p>
                    </div>
                    <div class="weui-cell__ft">
                    </div>
                </a>
            </div>
            <div class="weui-panel weui-panel_access">
                <div class="weui-panel__hd fs32 lh48 cr333">商品列表</div>
                <div class="weui-panel__bd">
                
            
                 <?php foreach ($cartList['items'] as $key=>$vo):?>
                    <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">
                        <div class="weui-media-box__hd">
                            <img class="weui-media-box__thumb" src="<?=$vo['skus']['thumbImg']?>" alt="<?=$vo['product_name']?>">
                        </div>
                        <div class="weui-media-box__bd">
                            <h4 class="weui-media-box__title fs32 lh48"><?=$vo['product_name']?></h4>
                            <p class="weui-media-box__desc fs28 lh38">数量：X<?=$vo['num']?></p>
                            <p class="weui-media-box__desc fs28 lh38">￥<?=$vo['sale_price']?>元</p>
                        </div>
                    </a>
                 <?php endforeach;?>
                </div>
                <div class="weui-panel__ft">
                    <a href="javascript:void(0);" class="weui-cell weui-cell_access weui-cell_link">
                        <div class="weui-cell__bd fs24 lh38">查看更多</div>
                        <span class="weui-cell__ft"></span>
                    </a>
                </div>
            </div>
            <div class="weui-cells">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p class="fs28 lh38">配送方式</p>
                    </div>
                    <div class="weui-cell__ft">
                        <p class="fs28 lh38">￥0.00</p>
                        <p class="fs28 lh38">普通快递</p>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">留言</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" placeholder="点击给商家留言">
                    </div>
                 </div>
            </div>
            <div class="weui-cells">
                <div class="weui-cell weui-cell_switch">
                    <div  class="weui-cell__bd">可用余额<span id="cash" >900</span>元</div>
                    <div class="weui-cell__ft">
                         <label for="switchCP" class="weui-switch-cp">
                            <input id="switchCP" class="weui-switch-cp__input" type="checkbox" checked="checked">
                            <div class="weui-switch-cp__box"></div>
                        </label>
                    </div>
                </div> 
            </div>
            <div class="weui-cells">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p class="fs28 lh38">商品金额</p>
                        <p class="fs28 lh38">运费</p>
                    </div>
                    <div class="weui-cell__ft">
                        <p class="fs28 lh38 crred">￥<?=$cartList['total']?></p>
                        <p class="fs28 lh38 crred">+ ￥0.00</p>
                    </div>
                </div>
                <div class="weui-cell" href="javascript:;">
                    <div class="weui-cell__ft weui-flex__item">
                        <p class="cr333 fs28 lh38">实际应付款：<em class="crred">￥<?=$cartList['amount']?></em></p>
                    </div>
                </div>
            </div>
            <div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary fs32" href="javascript:" id="showTooltips">订单提交</a>
            </div>
        </div>
    </div>
    
     <?php 
$this->registerJs(<<<JS
$('#switchCP').change(function() {
       $.ajax({
       type:'post',
       dataType:'json',
       url:'/cart/order',
       data:{money : $('#cash').html(),
       switch:$('#switchCP').prop('checked')
       },
       success:function(msg){
        
        if(msg.status==0){
         $.alert(msg.msg);
        }else{
           div.remove()
             ajax_cart_list();
         }
        } 
        
        });
     
    })
JS
);
?>