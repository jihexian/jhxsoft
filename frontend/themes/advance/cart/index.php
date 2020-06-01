<?php

use yii\helpers\Url;
use common\helpers\Tools;
?>
<header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item" style="margin: 0 0 0 .66rem;">
                    购物车
                </div>
                <span class="guanli">管理</span>
                <div style="margin-right: .3rem;"><i class="iconfont icon-mulu" id="mulu-bt"></i></div>
            </div>
        </header>
        <?=$this->render('../layouts/cart_menu')?>
        <section class="bottom-fixed jiesuan">
            <div class="weui-flex">
                <div class="box1">
                    <div class="weui_cells_checkbox">
                        <label class="weui-check__label" for="allCheck">
                            <div class="weui-cell__hd">
                                <input type="checkbox" class="weui-check allCheck" name="check_all_buy" id="allCheck">
                                <i class="weui-icon-checked"></i><span class="all">全选</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="weui-flex__item box2">
                    <div class="">
                        <p>总计：<b>￥<em class="total-price">0.00</em></b></p>
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
                        <label class="weui-check__label" for="allCheckDel">
                            <div class="weui-cell__hd">
                                <input type="checkbox" class="weui-check allCheck" name="check_all_del" id="allCheckDel">
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
        <section class="main" style="margin-bottom: 1.91rem;">
        	<?php if(!empty($items)): ?>    
            <ul class="weui_cells_checkbox cart-list">
            <?php foreach ($items as $shopKey=>$shop):?>  
                <li class="weui-cells shop-group-item">
                    <div class="shop-name">
                        <label class="weui-check__label" for="shop<?=$shopKey?>">
                            <div class="weui-cell__hd">
                                <input type="checkbox" class="weui-check goods-check shopCheck" name="shop-name" id="shop<?= $shop['shop']['id']?>">
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
                                    <label class="weui-check__label" for="pro<?=$shopKey.$key?>">
                                        <div class="weui-cell__hd">
                                            <input type="checkbox" value="<?=$vo['id']?>" <?=$vo['skusStatus']['status']!=1? "disabled='disabled'":"" ?>  name="cart[]" <?=$vo['selected']==1?'checked="checked"':'' ?>  class="weui-check goods-check goodsCheck" id="pro<?=$shopKey.$key?>">
                                            <i class="weui-icon-checked"></i>
                                        </div>
                                    </label>
                                    <div class="weui-cell__hd cartlist-img-box">
                                        <img src="<?=$vo['skus']['thumbImg']?>">
                                    </div>
                                    <div class="weui-cell__bd">
                                        <h1   class="cartlist-tit"><?=$vo['product_name']?></h1>
                                        <p class="cart-attr"><?= $vo['skusStatus']['status']==1? Tools::get_skus_value($vo['skus']['sku_values']):$vo['sku_values']?></p>
                                        <div class="cartlist-price">
                                            <?php if($vo['skusStatus']['status']==1):?>
                                                <p>￥<em class="price"><?=$vo['sale_price_real']?></em><?php if (Yii::$app->user->identity->type==3&&$vo['skus']['plus_price']==$vo['sale_price_real']) {echo '<span class="iconfont icon-huiyuan huiyuan_plus"></span>';}?></p>
                                                <div class="weui-cell__ft">                                          
                                                    <div class="weui-count">
                                                        <a class="weui-count__btn weui-count__decrease"></a>
                                                        <input class="weui-count__id" type="hidden" name="sku_id[]" value="<?=$vo['skus']['sku_id'] ?>"/> 
                                                        <input class="weui-count__stock" type="hidden" name="stock[]" value="<?=$vo['skus']['stock'] ?>"/>  
                                                        <input class="weui-count__prom_type" type="hidden" name="prom_type[]" value="<?= $vo['prom_type']?>"/> 
                                                        <input class="weui-count__prom_id" type="hidden" name="prom_id[]" value="<?= $vo['prom_id']?>"/>   
                                                        <input class="weui-count__number" type="number" value=<?=$vo['num'] ?> />
                                                        <a class="weui-count__btn weui-count__increase"></a>
                                                    </div> 
                                                </div>
                                            <?php else:?>
                                                <p class="fs24"><?=$vo['skusStatus']['msg']?></p>
                                                <a href="#" class="reset">重选</a>
                                            <?php endif;?>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="weui-cell__ft">
                                <a class="weui-swiped-btn weui-swiped-btn_warn delete-swipeout" href="javascript:">删除</a>
                                <a class="weui-swiped-btn weui-swiped-btn_default close-swipeout" href="javascript:">关闭</a>
                            </div>
                        </li>
                        <?php endforeach;?>  
                    </ul>
                </li>
                <?php endforeach;?>  
            </ul>
            <?php else: ?>
            <div class="tc cart-none" style="display: block">
                <i class="iconfont icon-gouwuche1"></i>
                <p style="">购物车什么都没有，赶紧去购物吧！</p>
                <a href="<?= Url::to(['site/index'])?>">去购物</a>
            </div>
            <?php endif; ?>
        </section>


 <?php 
$this->registerJs(<<<JS

    $(function(){        
        $(".shopCheck").each(function(){
            var goodsC = $(this).parents(".shop-group-item").find(".goods-check:checked"); 
            var goods = $(this).parents(".shop-group-item").find(".goodsCheck").not(':disabled'); //获取本店铺的所有可选商品
            if (goods.length == goodsC.length) { //如果选中的商品等于所有商品上面设置了disabel所有需要加1                      
                $(this).prop('checked', true); //店铺全选按钮被选中
                if ($(".shopCheck").length == $(".shopCheck:checked").length) { //如果店铺被选中的数量等于所有店铺的数量
                    $(".allCheck").prop('checked', true); //全选按钮被选中
                } else {
                    $(".allCheck").prop('checked', false); //else全选按钮不被选中 
                }
            } else { //如果选中的商品不等于所有商品
                $(this).prop('checked', false); //店铺全选按钮不被选中
                $(".allCheck").prop('checked', false); //全选按钮也不被选中                     
            }            
        })
        getPriceTotal();
    });
 //购物车物品数量
    var MAX,
        MIN = 1;
    var beforeAjaxNum = new Map();
    $('.weui-count__decrease').click(function(e) {
        if($(this).attr("disabled")=='disabled'){//如果按钮是不可用的
            return;
        }  
        
        var decrease = $(this);
        $(this).attr("disabled",true); 
        var inputX = $(e.currentTarget).parent().find('.weui-count__number');
        var number = parseInt(inputX.val() || "0") - 1;        
        if (number < MIN){
            number = MIN; 
            decrease.attr("disabled",false);
        } 
        var id = $(this).parents('.pro-item').find("input[name='cart[]']").val();
        $.ajax({
            type:'post',
            dataType:'json',
            url:'/cart/change',
            async:true,
            data:{id:id,num:-1},
            success:function(data){        
                if(data.status==1){
                    inputX.val(number);
                    getPriceTotal();           
                }
               decrease.attr("disabled",false);
            }             
        });

    })
    $('.weui-count__increase').click(function(e) {  
        if($(this).attr("disabled")=='disabled'){//如果按钮是不可用的
            return;
        }  
        var increase = $(this);
        $(this).attr("disabled",true);    
        var inputX = $(e.currentTarget).parent().find('.weui-count__number');
        var number = parseInt(inputX.val() || "0") + 1;
        var inputStock = $(e.currentTarget).parent().find('.weui-count__stock');
        //var inputStock = $(e.currentTarget).parent().find('.weui-count__stock');
        var stock = inputStock.val();
        if(stock<number){
            $.toast('库存不足','forbidden');
            increase.attr("disabled",false);
            return;
        }
        var id = $(this).parents('.pro-item').find("input[name='cart[]']").val();
        $.ajax({
            type:'post',
            dataType:'json',
            url:'/cart/change',
            async:true,
            data:{id:id,num:1},
            success:function(data){        
                if(data.status==1){
                    inputX.val(number);
                    getPriceTotal();                 
                }
               increase.attr("disabled",false);
            }             
        });
        
    })

    
    $('.close-swipeout').click(function() {
        $(this).parents('.weui-cell').swipeout('close')
    })
    
    //点击商品按钮
    $(".goodsCheck").click(function() {
        var cancheck = $(this).prop("disabled");    
        if($(this).prop("disabled")){//如果按钮是不可用的
            return;
        }
        $(this).attr("disabled",true);
        var ids = new Array();
        var goodsCheck = $(this);
        $("input[name='cart[]']:checked").each(function() {
            ids.push($(this).val());            
        });
        $.ajax({
            type:'post',
            dataType:'json',
            url:'/cart/calculator',
            async:true,
            data:{cart_ids:ids},
            success:function(data){        
                if(data.status==1){
                    var goods = goodsCheck.closest(".shop-group-item").find(".goodsCheck").not(':disabled'); //获取本店铺的所有可选商品
                    var goodsC = goodsCheck.closest(".shop-group-item").find(".goodsCheck:checked"); //获取本店铺所有被选中的商品
                    var Shops = goodsCheck.closest(".shop-group-item").find(".shopCheck"); //获取本店铺的全选按钮
                    if (goods.length+1 == goodsC.length) { //如果选中的商品等于所有商品上面设置了disabel所有需要加1                      
                      Shops.prop('checked', true); //店铺全选按钮被选中
                      if ($(".shopCheck").length == $(".shopCheck:checked").length) { //如果店铺被选中的数量等于所有店铺的数量
                        $(".allCheck").prop('checked', true); //全选按钮被选中
                        getPriceTotal();
                      } else {
                        $(".allCheck").prop('checked', false); //else全选按钮不被选中 
                        getPriceTotal();
                      }
                    } else { //如果选中的商品不等于所有商品
                      Shops.prop('checked', false); //店铺全选按钮不被选中
                      $(".allCheck").prop('checked', false); //全选按钮也不被选中
                      // 计算
                      getPriceTotal();
                    }                 
                }
                goodsCheck.attr("disabled",cancheck);
            }             
      });
      });

  // 点击店铺按钮
  $(".shopCheck").click(function() {
        if($(this).prop('disabled')){
            return;
        }
        $(this).attr("disabled",true);
        var ids = new Array();
        var shopCheck = $(this);
    if ($(this).prop("checked") == true) { //如果店铺按钮被选中    
      $(this).parents(".shop-group-item").find(".goods-check").not(':disabled').each(function() {
        ids.push($(this).val());
      });  
      $("input[name='cart[]']:checked").each(function() {
            ids.push($(this).val());            
      });        
      $.ajax({
        type:'post',
        dataType:'json',
        url:'/cart/calculator',
        async:true,
        data:{cart_ids:ids},
            success:function(data){        
                if(data.status==1){
                    shopCheck.parents(".shop-group-item").find(".goods-check").not(':disabled').prop('checked', true); //店铺内的所有可选商品按钮也被选中
                    if ($(".shopCheck").length == $(".shopCheck:checked").length) { //如果店铺被选中的数量等于所有店铺的数量
                        $(".allCheck").prop('checked', true); //全选按钮被选中
                        getPriceTotal();;
                    } else {
                        $(".allCheck").prop('checked', false); //else全选按钮不被选中
                        getPriceTotal();
                    }                    
                }
                shopCheck.attr("disabled",false);
            }             
      });
    } else { //如果店铺按钮不被选中
      $("input[name='cart[]']:checked").each(function() {
            ids.push($(this).val());            
      });  
      $(this).parents(".shop-group-item").find(".goods-check").not(':disabled').each(function() {
        var index=ids.indexOf($(this).val());
        if(index>-1){
            ids.splice(index, 1);
        }
      });
      $.ajax({
        type:'post',
        dataType:'json',
        url:'/cart/calculator',
        async:true,
        data:{cart_ids:ids},
            success:function(data){        
                if(data.status==1){
                     shopCheck.parents(".shop-group-item").find(".goods-check").prop('checked', false); //店铺内的所有商品也不被全选
                     $(".allCheck").prop('checked', false); //全选按钮也不被选中
                     getPriceTotal();                    
                }
                shopCheck.attr("disabled",false);
            }             
      }); 
     
    }
  });

    // 点击全选按钮
    $(".allCheck").click(function(){
        if($(this).prop('disabled')){
            return;
        }
        $(this).attr("disabled",true);
        var ids = new Array();
        if ($(this).prop("checked") == true) { //如果全选按钮被选中   
            
            $(".goodsCheck").not(':disabled').each(function() {
                ids.push($(this).val());
            });
             $.ajax({
                type:'post',
                dataType:'json',
                url:'/cart/calculator',
                async:true,
                data:{cart_ids:ids},
                    success:function(data){        
                        if(data.status==1){
                            $(".goods-check").not(':disabled').prop('checked', true); //所有按钮都被选中
                            getPriceTotal();  
                            
                        }
                        $(".allCheck").attr("disabled",false);
                    }             
           });
            
        }else {
            $.ajax({
                type:'post',
                dataType:'json',
                url:'/cart/calculator',
                async:true,
                data:{cart_ids:ids},
                    success:function(data){        
                        if(data.status==1){
                            $(".goods-check").prop('checked', false); //else所有按钮不全选
                            getPriceTotal();                            
                        }
                        $(".allCheck").attr("disabled",false);
                    }             
           });                       
        }
        $(".shopCheck").change(); //执行店铺全选的操作
    });

    //获取总价
    function getPriceTotal() {
        var cart_ids = new Array();
        var priceTotal = 0;
        $("input[name='cart[]']:checked").each(function() {
            var num = $(this).parents('li.pro-item').find("input.weui-count__number").val();
            var price = $(this).parents('li.pro-item').find('.price').text();
            priceTotal += parseInt(num) * parseFloat(price);
            
        })        
        $('.total-price').html(priceTotal.toFixed(2));    
        
    };

    

    //切换删除底部和结算底部
    $('.guanli').click(function(){
        var w = $(this).html();
        if(w === '管理'){
            $(this).html('完成');
            $('.jiesuan').hide();
            $('.shanchu').show();
            //$('.goods-check').prop('checked', false);
        }else{
            $(this).html('管理');
            $('.jiesuan').show();
            $('.shanchu').hide();
        }

    })

    //底部删除按钮 删除商品
    $('#del').click(function(){
        var ids = new Array();
        $("input[name='cart[]']:checked").each(function() {
            ids.push($(this).val());            
        }); 
        var len = ids.length;
        if(len<=0){
            $.toast.prototype.defaults.duration = 900;
            $.toast('请选择需要删除的商品','forbidden');
            return;
        }  
        $.confirm({
          title: '温馨提示',
          text: '确认要删除这'+len+'件商品吗？',
          onOK: function () {
            $.ajax({
                type:'post',
                dataType:'json',
                url:'/cart/del',
                async:false,
                data:{ids:ids},
                success:function(data){        
                    if(data.status==1){
                        location.reload();
                    }   
                }
            });
            
          },
          onCancel: function () {
          }
        });
    });
    $('.delete-swipeout').click(function() {
        var item = $(this);
        var ids = new Array();
        var id = item.parents('.pro-item').find("input[name='cart[]']").val();
        ids.push(id);     
        $.confirm({
          title: '温馨提示',
          text: '确认要删除该商品吗？',
          onOK: function () {
            console.log(id);
            $.ajax({
                type:'post',
                dataType:'json',
                url:'/cart/del',
                async:false,
                data:{ids:ids},
                success:function(data){        
                    if(data.status==1){
                        item.parents('.weui-cell').remove();
                        location.reload();
                    }   
                }
            });
            
          },
          onCancel: function () {
          }
        });
    });
   

    //改变数量
    function change(id,num){
        var flag=0;
        $.ajax({
            type:'post',
            dataType:'json',
            url:'/cart/change',
            async:false,
            data:{id:id,num:num},
            success:function(data){        
                if(data.status==1){
                    flag=1;
                }
            }             
        });
        return flag;
    }

JS
);
?>

