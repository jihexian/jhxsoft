<?php
/**
 * Author wsyone wsyone@faxmail.com
 * Time:2018-11-10
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use common\helpers\Util;
use yii\helpers\Url;
?>
        <section class="main" style="margin-bottom: 1.91rem;">
            <ul class="weui_cells_checkbox cart-list">
            <?php foreach ($items as $shopKey=>$shop):?>      
                <li class="weui-cells shop-group-item">
                 <div class="shop-name">
                        <label class="weui-check__label" for="shop1">
                            <div class="weui-cell__hd">
                                <input type="checkbox" class="weui-check goods-check shopCheck" name="shop-name" id="<?= $shop['shop']['id']?>">
                                <i class="weui-icon-checked"></i>
                            </div>
                        </label>
                        <a href=""><?= $shop['shop']['name']?></a>
                    </div>  
                    <ul>     
                    	<?php foreach ($shop['data'] as $key=>$vo):?>                           
                       <li class="weui-cell weui-cell_swiped pro-item">
                            <div class="weui-cell__bd">
                                <div class="weui-cell">
                                    <label class="weui-check__label" for="s<?=$key+1?>">
                                    
                                        <div class="weui-cell__hd">
                                        <input type="checkbox" value="1"  name="cart_select[<?=$vo['id']?>]" <?=$vo['selected']==1?'checked="checked"':'' ?>  onclick="ajax_cart_list()" class="weui-check goods-check" id="s<?=$key+1?>">
                                        <i class="weui-icon-checked"></i>
                                    </div>
                                    </label>
                                    <div class="weui-cell__hd cartlist-img-box">
                                        <img src="<?=$vo['skus']['thumbImg']?>">
                                    </div>
                                    <div class="weui-cell__bd cartlist-r-box">
                                        <h1   class="cartlist-tit"><?=$vo['product_name']?></h1>
                                        <p class="cart-attr"><?=$vo['sku_values']?></p>
                                        <div class="cartlist-price">
                                            <p>￥<em class="price"><?=$vo['sale_price']?></em></p>
                                            <div class="weui-cell__ft">
                                                <div class="weui-count">
                                                    <a href="javascript:;" onclick="switch_num(-1,<?=$vo['id']?>,<?=$vo['prom_id']?$vo->skus->prom->goods_num:$vo['skus']['stock']?>);" class="weui-count__btn weui-count__decrease"></a>
                                                    <input class="weui-count__number" name="goods_num[<?=$vo['id']?>]" id="goods_num[<?=$vo['id']?>]" type="number" value="<?=$vo['num']?>" />
                                                    <a href="javascript:;" onclick="switch_num(1,<?=$vo['id']?>,<?=$vo['prom_id']?$vo->skus->prom->goods_num:$vo['skus']['stock']?>);"  class="weui-count__btn weui-count__increase"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="weui-cell__ft">
                                <a class="weui-swiped-btn weui-swiped-btn_warn delete-swipeout" id="<?=$vo['id']?>"   href="javascript:">删除</a>
                                <a class="weui-swiped-btn weui-swiped-btn_default close-swipeout" href="javascript:">关闭</a>
                            </div>
                        </li>
                       <?php endforeach;?>  
                    </ul>
                </li>
              <?php endforeach;?>  
            </ul>
            
            <div class="tc cart-none" style="display: none">
                <i class="iconfont icon-gouwuche1"></i>
                <p style="">购物车什么都没有，赶紧去购物吧！</p>
                <a href="javascript:;">去购物</a>
            </div>
        </section>
        
        
        <section class="bottom-fixed jiesuan">
            <div class="weui-flex">
                <div class="box1">
                    <div class="weui_cells_checkbox">
                        <label class="weui-check__label" for="all_buy">
                            <div class="weui-cell__hd">
                                <input type="checkbox" onclick="chkAll_onclick();" class="weui-check" name="check_all_buy" id="all_buy">
                                <i class="weui-icon-checked"></i><span class="all">全选</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="weui-flex__item box2">
                    <div class="">
                        <p>总计：<b>￥<em class="total-price"><?=$sale_real_total?></em></b></p>
                        <p>（不含运费）</p>
                    </div>
                </div>
                <div class="box3">
                    <a onclick="selcart_submit();" href="javascript:">去结算</a>
                </div>
            </div>
        </section>
        <section class="bottom-fixed shanchu">
            <div class="weui-flex">
                <div class="box1 weui-flex__item">
                    <div class="weui_cells_checkbox">
                        <label class="weui-check__label" for="all_del">
                            <div class="weui-cell__hd">
                                <input type="checkbox"   class="weui-check" name="check_all_del" id="all_del">
                                <i class="weui-icon-checked"></i><span class="all">全选</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="box3">
                     <a href="javascript:;" id="del">删除</a>
                </div>
            </div>
        </section>
        <?php 
$this->registerJs(<<<JS
    var is_checked = true;
    $('.goods-check').each(function(){
     //判断商品是否选中
    	  if(!$(this).prop('checked'))//不是全选时
    	  {
    	       is_checked = false;
    	       return false;
    	  }
    });

    if(is_checked){
      $('#all_buy').prop('checked','true')
    }

 // 点击店铺按钮
  $(".shopCheck").click(function() {
    if ($(this).prop("checked") == true) { //如果店铺按钮被选中
      $(this).parents(".shop-group-item").find(".goods-check").prop('checked', true); //店铺内的所有商品按钮也被选中
      if ($(".shopCheck").length == $(".shopCheck:checked").length) { //如果店铺被选中的数量等于所有店铺的数量
        $(".allCheck").prop('checked', true); //全选按钮被选中
         getPriceTotal();;
      } else {
        $(".allCheck").prop('checked', false); //else全选按钮不被选中
       getPriceTotal();
      }
    } else { //如果店铺按钮不被选中
      $(this).parents(".shop-group-item").find(".goods-check").prop('checked', false); //店铺内的所有商品也不被全选
      $(".allCheck").prop('checked', false); //全选按钮也不被选中
       getPriceTotal();;
    }
  });

 

  $('.weui-cell_swiped').swipeout()
  $('.delete-swipeout').click(function() {
  div=$(this).parents('.weui-cell');
       $.ajax({
       type:'post',
       dataType:'json',
       url:'/cart/del',
       data:{id : $(this).attr('id')},
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
   $('.close-swipeout').click(function() {
        $(this).parents('.weui-cell').swipeout('close')
    })
JS
);
?>

        
 

