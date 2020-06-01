<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年11月16日 上午10:08:13
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use common\helpers\Tools;
use common\helpers\Util;
use yii\helpers\Url;
use yii\helpers\Json;
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
              <?php if(!empty($address)):?>
                <a class="weui-cell weui-cell_access" href="<?=Url::to(['address/index','source'=>'cart','type'=>$type,'id'=>"$id"])?>">
                    <div class="iconfont icon-dizhi"></div>
                    <div class="weui-cell__bd">
                        <p class="fs28 cr333"><?php echo($address['userName']);?>&nbsp;&nbsp;<?=$address['telNumber']?></p>
                        <p class="fs24 cr999 info"><?=$address['province']['name'].$address['city']['name'].$address['county']['name'].$address['detailInfo']?></p>
                    </div>
                    <div class="weui-cell__ft"><input name="aid" hidden="hidden" id="aid" value="<?=$address['id']?>">
                    </div>
                </a>
                <?php else:?>
                <a href="<?=Url::to(['address/index','source'=>'cart','type'=>$type,'id'=>$id])?>" class="add-addr-btn"><i class="iconfont icon-xinzeng"></i>新增收货地址</a>
              <?php endif;?>
            </div>
            <?php foreach ($items as $shopKey=>$shop):?> 
            
            <div class="weui-cells" id="shop_<?=$shopKey?>">
                <div class="weui-panel weui-panel_access gd-list">
                    <div class="weui-panel__hd fs32 lh48 cr333"><?= $shop['shop']['name']?></div>
                    <div class="weui-panel__bd">
                        <?php foreach ($shop['data'] as $key=>$vo):?>
                        <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">
                            <div class="weui-media-box__hd">
                                <img class="weui-media-box__thumb" src="<?=$vo['skus']['thumbImg']?>" alt="">
                            </div>
                            <div class="weui-media-box__bd">
                                <h4 class="weui-media-box__title"><?=$vo['product_name']?></h4>
                                <p class="weui-media-box__desc"><?= $vo['skusStatus']['status']==1? Tools::get_skus_value($vo['skus']['sku_values']):$vo['sku_values']?></p>
                                <div class="weui-media-box__desc weui-media-box__bd__btn"><p>￥<?=$vo['sale_price_real']?>元</p><p>数量：X<?=$vo['num']?></p></div>
                            </div>
                        </a>
                        <?php endforeach;?>
                    </div>
                </div>
               
                <div class="weui-cell weui-cell_access brn">
                    <div class="weui-cell__bd">
                        <p class="fs28 lh38">配送方式</p>
                    </div>
                     <?php if(!empty($address)):?>
                    <a href="<?=Url::to(['point/index','address'=>$addressId,'type'=>$type,'id'=>$id,'city'=>$address->city->name,'area'=>$address->county->name])?>" class="weui-cell__ft">
                    <?php else:?> 
                      <a href="<?=Url::to(['point/index','address'=>$addressId,'type'=>$type,'id'=>$id])?>" class="weui-cell__ft">
                    <?php endif;?>
                        <p class="fs28 lh38">￥<?=$shop['shipping_price']?></p>
                        <p class="fs28 lh38"><?php if($delivery_id==0||empty($delivery_id)):?>物流配送<?php elseif($delivery_id==2):?>门店自提<?php endif;?></p>
                    </a>
                    
                </div>
                <div class="weui-cell coupon">
                    <div class="weui-cell__bd">
                        <p class="fs28 lh38">优惠券</p>
                    </div>
                    <div class="weui-cell__ft">
                        <p class="fs28 lh38 coupon_name"><?=count($shop['coupon']['enable'])?>张可用<i class="weui-cell__ft"></i></p>
                        <input type="hidden" name="usecoupon<?=$shopKey?>" value="">
                    </div>
                <div class="coupon-popup" style="display: none">
                <div class="order_tab">
                    <a id="can-use" class="active">可使用优惠券(<?=count($shop['coupon']['enable'])?>)</a>
                    <a id="no-use">不可使用优惠券(<?=count($shop['coupon']['disable'])?>)</a>
                </div>
                <div class="can-use">
                    <?php foreach ($shop['coupon']['enable'] as $k=>$v):?>
                    <div class="cell-box coupon coupon-list" id="<?=$v['id']?>" shop="<?=$v['coupon']['shop_id']?>">
                        <a  class="weui-flex">
                            <div class="coupon-content">
                                <h2><?=$v['coupon']['use_money']?></h2>
                                <p>无使用门槛</p>
                            </div>
                            <div class="weui-flex__item coupon-body">
                                <div class="coupon-name">
                                <span><?=$v['coupon']['name']?></span>
                                <p>有效期：<?=date('Y-m-d',$v['coupon']['use_start']).'-'.date('Y-m-d',$v['coupon']['use_end'])?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach;?>
                </div>
                <div class="no-use" style="display: none;">
                    <?php foreach ($shop['coupon']['disable'] as $k=>$v):?>
                    <div class="cell-box coupon coupon-list">
                        <a  class="weui-flex">
                            <div class="coupon-content">
                                <h2><?=$v['coupon']['use_money']?></h2>
                                <p>无使用门槛</p>
                            </div>
                            <div class="weui-flex__item coupon-body">
                                <div class="coupon-name">
                                <span><?=$v['coupon']['name']?></span>
                                <p>有效期：<?=date('Y-m-d',$v['coupon']['use_start']).'-'.date('Y-m-d',$v['coupon']['use_end'])?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach;?>
                </div>
                <div class="coupon-button">
                    <span class="van-button__text">不使用优惠</span>
                </div>
            </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">留言</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" id="message<?=$shopKey?>" name="message" placeholder="点击给商家留言">
                    </div>
                 </div>
                <div class="weui-cell money-detail">
                    <div class="weui-cell__bd">
                        <p class="fs28 lh38">商品金额</p>
                        <p class="fs28 lh38">运费</p>
                        <p class="fs28 lh38">优惠券</p>
                    </div>
                    <div class="weui-cell__ft <?='shopId_'.$shop['shop']['id']?>">
                        <p class="fs28 lh38 crred sale_real_total">￥<em><?=$shop['sale_real_total']?></em></p>
                        <p class="fs28 lh38 crred shipping_price">+ ￥<em><?=$shop['shipping_price']?></em></p>
                        <p class="fs28 lh38 crred coupon_money">- ￥<em>0</em></p>
                    </div>
                </div>
                <div class="weui-cell" href="javascript:;">
                    <div class="weui-cell__ft weui-flex__item">
                        <p class="cr333 fs28 lh38"><span class="mgr30">共<?=$shop['buy_total']?>件商品</span>小计：<em  class="crred">￥</em><em class="crred subtotal"><?=bcadd($shop['shipping_price'], $shop['sale_real_total'],2)?></em></p>
                    </div>
                </div>
            </div>
           <?php endforeach;?>  
           
            <div class="weui-cells"  <?php if($scoreMoney>=0):?>style="display:none"<?php endif;?> >
                <div class="weui-cell weui-cell_switch">
                    <div class="weui-cell__bd">可用积分<?=$scoreMoney?>元<input type="hidden" name='socreMoney' value="<?=$scoreMoney?>"></div>
                    <div class="weui-cell__ft">
                         <label for="switchCP" class="weui-switch-cp">
                            <input id="switchCP" class="weui-switch-cp__input" type="checkbox" name="score">
                            <div class="weui-switch-cp__box"></div>
                        </label>
                    </div>
                </div> 
            </div>
           
            <section class="bottom-fixed">
                <div class="weui-flex  order-all-count">
                    <div class="weui-flex__item">
                        <div class="box2">
                            <p>共<span class="crred"><?=$buy_total?></span>件，总金额：<b>￥<em class="total-price"><?=$amount?></em></b></p>
                        </div>
                    </div>
                    <div class="box3">
                        <a href="javascript:" id="submit">提交订单</a>
                    </div>
                </div>
            </section>
            
        </div>
    </div>
<?php 
$shops = array();
foreach ($items as $shopKey=>$shopCarts){
    $shop = array();
    $shop['id'] = $shopKey;
    $cartIds = array();
    foreach ($shopCarts['data'] as $cartKey=>$cart){
        array_push($cartIds, $cart['id']);
    }
    $shop['cart_ids'] = $cartIds;
    array_push($shops, $shop);
}
$shops = Json::encode($shops);
$type=yii::$app->request->get('type',0);
$point_id= yii::$app->request->get('point_id',0);
$delivery_id = yii::$app->request->get('delivery_id',0);
$this->registerJs(<<<JS

//选择优惠券
    var couponId='0';
    var couponMoney='0';
    var couponName='';
    $('.coupon').click(function(){
        $(this).children('.coupon-popup').show();
    });
//不使用优惠
    $('.coupon-button').click(function(){
        shopId=$(this).attr('shop');
        couponId=0;
        couponMoney=0;
        couponName='请选择优惠券';
        $('input[name="usecoupon'+shopId+'"]').val('');
        totalPrice(shopId,couponMoney,couponName);
        $(this).parent().hide(1);
    });
//可使用列表和不可使用列表
    $('.order_tab>a').click(function(){
           $(this).addClass('active').siblings().removeClass('active');
           if($(this).attr('id')=='can-use'){
                $(this).parent().parent().children('.can-use').show();
                $(this).parent().parent().children('.no-use').hide();
            }else{
                $(this).parent().parent().children('.can-use').hide();
                $(this).parent().parent().children('.no-use').show();
            }
    });
    //选择优惠券
    $('.can-use>div').click(function(){
        couponId=$(this).attr('id');
        shopId=$(this).attr('shop');
        couponMoney=$(this).find('.coupon-content').find('h2').text();
        couponName=$(this).find('.coupon-name').find('span').text();
        $("input[name='usecoupon"+shopId+"']").val(couponId);
        totalPrice(shopId,couponMoney,couponName)
        $('.coupon-popup').hide(1);
        
    });
    function totalPrice(shopId,couponMoney,couponName){
        var shopDivID = '#shop_'+shopId;
        $(shopDivID).find('.coupon_name').text(couponName);
        $(shopDivID).find('.coupon_money').find('em').text(couponMoney);
        subtotal=parseFloat($(shopDivID).find('.sale_real_total').find('em').text());
        subtotal+=parseFloat($(shopDivID).find('.shipping_price').find('em').text());
        subtotal-=couponMoney;
        subtotal=subtotal.toFixed(2);
        $(shopDivID).find('.subtotal').text(subtotal);
        result=0;
        $('.subtotal').each(function(){
           result+=parseFloat($(this).text());
        });
        result=result.toFixed(2);
        $('.total-price').text(result);
    }



    
//提交表单

$('#submit').click(function(){
    //地址
    var aid=$('#aid').val();
    if(aid==undefined){
        $.toast("请选择收货地址", "forbidden");
        return;
    }
     
    //积分兑换金额 
    var scoreMoney = 0;
    if( $('#switchCP').prop('checked')){
       scoreMoney = $('input[name="scoreMoney"]').val();
    }
    var shops = $shops;
    $(shops).each(function(){
       var id = this.id;
       var message=$('#message'+id).val();//留言        
       this.mark = message;  
       var citem_id = $("input[name='usecoupon"+id+"']").val();
       this.citem_id = citem_id;
    });

    $.ajax({
       type:'post',
       dataType:'json',
       url:'/cart/checkout',
       data:{'shops':shops,'scoreMoney':scoreMoney,'aid':aid,'type':"$type",'delivery_id':"$delivery_id",'point_id':"$point_id"},
       success:function(e){
          if(e.status==1){
              $(location).attr('href', '/cart/cart4?parent_sn='+e.msg); 
          }else{
            $.alert(e.msg)
         }}      
    });
});


JS
);
?>
