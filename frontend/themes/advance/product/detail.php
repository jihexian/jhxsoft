<?php
/**
 *
 * Author vamper 944969253@qq.com
 * Time:2018年11月16日 下午5:26:13
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Json;
use yii\helpers\Url;
?>
<style type="text/css">
  .vr{margin-right: 10px;background-color: #3385ff; padding: .13rem .3rem;border-radius: 5px;color: #fff;}
  .goods-row-pintuan{background-color: #fff;width: 100%;height: 60px;}
  .pintuan{position: relative;}
  .pintuan-left{background:linear-gradient( to right ,#fd024b, #fe4d2a);border-radius: 15px;padding: 8px 0 8px 8px;width: 59%;position: absolute;left: 0;z-index: 2;box-sizing: border-box;}
  .pintuan-price{display:flex; color: #fff;}
  .price-bold{font-size: 18px;font-weight: 600;}
  .orgin-price{color: #f9f9f9;margin-top: 5px;}
  .pintuan-num{border: 1px solid #fff;margin-left: 10px;border-radius: 3px;padding:1px 3px;}
  .pintuan-right{background-color: #fff5ea;border-top-right-radius: 15px;border-bottom-right-radius: 15px;padding: 8px 8px 8px 6%;width: 45%;position: absolute;right: 0;box-sizing: border-box;text-align: center;color: #fd1d3f;font-size: 14px;}
  .remain-time{margin-top: 10px;}
  .remain-time span{background-color: #fd1d3f; color: #fff;padding: 2px 2px;margin: 0 2px;border-radius: 3px;}
  .pintuan-min-price{margin-bottom: 3px;}
  /**/
  .pintuan-info{padding:.3rem .2rem;}
  .pintuan-item{display: flex;align-items: center;justify-content: space-between;margin-bottom: 15px;}
  .pintuan-member{display: flex;overflow: hidden;width: 105px;height: 40px;}
  .pintuan-member img{width: 35px;}
  .pintuan-title{font-weight: 600;border-left: 3px solid #f6705e;padding-left: 10px;margin-bottom: 15px;}
  .pintuan-link{background-color: #f2350c;  border-radius: 30px;padding: 8px 15px;}
  .pintuan-link a{color: #fff;}
  .need-people{margin-bottom: 5px;}
  .need-people span{color: #f2350c;}
  .laft-time{color: #999;}
  .rule{display: flex;justify-content: space-between;border-top: 1px solid #f9f9f9;padding-top: .3rem;}
  .rule a{color: #000;}
  .rule .iconfont{color: #666;}
  .rule .leader{font-weight: 600;margin-right: 10px;}
</style>
<header class="top-fixed">
	<div class="weui-flex top-box">
		<div onclick="javascript:history.back(-1);">
			<i class="iconfont icon-fanhui"></i>
		</div>
		<div class="weui-flex__item">
			<div class="weui-flex" style="padding: 0 0 0 .65rem;">
				<div class="weui-flex__item">商品详情</div>
<!-- 				<div class="weui-flex__item">详情</div>
				<div class="weui-flex__item">推荐</div>
				<div class="weui-flex__item">
					<a href="fupinbang.html">扶贫榜</a>
				</div> -->
			</div>
		</div>
		<div class="weui-flex mgr20">
			<i class="iconfont icon-fenxiang weui-flex__item fenxiang"></i>
			<i class="iconfont icon-mulu1 weui-flex__item" id="mulu-bt"></i>
		</div>
	</div>
</header>


<aside class="goods-nav hide">
            <ul>
                <li><a href="<?=Url::to(['site/index'])?>"><i class="iconfont icon-shouye"></i>首页</a></li>
                <!-- <li><a href=""><i class="iconfont icon-kefu1"></i>搜索</a></li> -->
                <li><a href="<?=Url::to(['member/index'])?>"><i class="iconfont icon-renwu"></i>个人中心</a></li>
                <li><a href="<?=Url::to(['order/all'])?>"><i class="iconfont icon-dingdan"></i>全部订单</a></li>
            </ul>

        </aside> 

<section class="bottom-fixed">
	<footer class="goods-bt">
		<div class="weui-flex">

			<a href="<?= \yii\helpers\Url::to(['shop/index','shop_id'=>$data['product']['shop_id']])?>" class="weui-flex__item">

			 <i	class="iconfont icon-shouye"></i>
				<p class="">店铺</p>
			</a> <a href="tel::<?=$data['product']['shop']['tel']?>" class="weui-flex__item">
			 <i	class="iconfont icon-kefu"></i>
				<p class="">客服</p>
			</a> <a href="<?= \yii\helpers\Url::to(['cart/index'])?>" class=""> 
			<i	class="iconfont icon-06 hint-num"><em id="cart-num" ><?=$num?></em></i>

				<p class="">购物车</p>
			</a>
		</div>
		 <div>
			<a href="javascript:;" class="btShow">加入购物车</a>
		</div>
		<div>
			<a href="javascript:;" class="btShow">立即购买</a>
		</div>
 <!--    <div>
      <a href="javascript:;" class="btShow">
        <div class="pintuan-min-price">￥49995.66</div>
        单独购买
      </a>
    </div>
    <div>
      <a href="javascript:;" class="btShow">
        <div class="pintuan-min-price">￥49995.66</div>
        我要开团
      </a>
    </div> -->
	</footer>
</section>
<section class="main" style="margin-bottom: 1.5rem;">
	<div class="swiper-container detail-swiper mgt68">
		<div class="swiper-wrapper">
		<?php if(count($data['product']['image'])>0):?>
			<?php foreach ($data['product']['image'] as $key ):?>
            <div class="swiper-slide bgf">
				<img src="<?=$key['thumbImg']?>" alt="" class="block">
			</div>
		<?php endforeach;?>
		<?php else:?>
		 <div class="swiper-slide bgf">
				<img src="<?=Yii::$app->params['defaultImg']['default']?>" alt="" class="block">
			</div>
        </div>
        <?php endif;?>
		<!-- Add Pagination -->
		<div class="swiper-pagination"></div>
	</div>
<!--   <div class="goods-row-pintuan">
    <div class="pintuan">
      <div class="pintuan-left">
        <div class="pintuan-price">
          <div class="price-bold">￥1099.666</div>
          <div class="pintuan-num"><icon class="iconfont icon-renwu"></icon><span>2人拼</span></div>
        </div>
        <div class="orgin-price">单买价￥1099.666</div>
      </div>
      <div class="pintuan-right">
        <div class="remain-text">距拼购结束还剩：</div>
        <div class="remain-time">
          <em id = 'day'>0</em>天<span id = 'hour'>00</span>:<span id = 'minute'>00</span>:<span id = 'second'>00</span>
        </div>
      </div>
    </div>
  </div> -->
	<div class="goods-row1">
		<div class="weui-flex">
			<h3 class="weui-flex__item"><?php echo $data['product']['name'];?></h3>
			<a id="collection"
			<?=$data['isFavorite']==0?'':'class="red"'?>>
			 <i class="iconfont icon-redshoucang"></i>
				<p>收藏</p>
			</a>
		</div>
	</div>
	<div class="goods-row2">
		<div>
			<p class="crred" id="goods_price" max='<?=$data['product']['max_price']?>' min='<?=$data['product']['min_price']?>' >￥<?=$data['product']['min_price']?></p>
			<!-- <p>积分可抵现</p> -->
         <div class="donation">
         <?php if($data['product']['shop']['percent']): ?>
              <p>捐赠<?=$data['product']['shop']['percent']*100?>%的扶贫基金</p>
         <?php endif;?>
     </div>
               
		</div>
		<div class="goods-count">
			<p>销量：<?=$data['product']['sale'];?></p>
			<p id='goods_stock' data='<?=$data['product']['stock']?>'>库存：<?=$data['product']['stock']?></p>
		</div>
	</div>

	<div class="goods-row4 mgb20">
		<div class="weui-cell__hd">
			<label for="" class="weui-label cr999">已选</label>
		</div>
		<div class="weui-cell__bd">
			<label id="goodsAttr">请选择规格</label>
		</div>
		<div>
			<i class="iconfont icon-mulu1 cr999 btShow"></i>
		</div>
	</div>
 <!-- <div class="pintuan-info mgb20 bgf">
    <div class="pintuan-title">开团记录</div>
    <div class="pintuan-item">
      <div class="pintuan-member">
        <div><image src="http://www.yjshop.com/storage/upload/20190703/adOgSTESqPchf6T8T0FfnZ3x1IWT_SB-EB5ET_AF.png"></image></div>
        <div><image src="http://www.yjshop.com/storage/upload/20190703/adOgSTESqPchf6T8T0FfnZ3x1IWT_SB-EB5ET_AF.png"></image></div>
        <div><image src="http://www.yjshop.com/storage/upload/20190703/adOgSTESqPchf6T8T0FfnZ3x1IWT_SB-EB5ET_AF.png"></image></div>
        <div><image src="http://www.yjshop.com/storage/upload/20190703/adOgSTESqPchf6T8T0FfnZ3x1IWT_SB-EB5ET_AF.png"></image></div>
      </div>
      <div>
        <div class="need-people">还差<span>1人</span>成团</div>
        <div class="laft-time" data-time="546">剩余<span class="hour1">00</span>:<span class="minute1">00</span>:<span class="second1">00</span></div>
      </div>
      <div>
        <div class="pintuan-link"><a href="<?=Url::to(['product/pintuan-detail'])?>">去参团</a></div>
      </div>
    </div>
    <div class="pintuan-item">
      <div class="pintuan-member">
        <div><image src="http://www.yjshop.com/storage/upload/20190703/adOgSTESqPchf6T8T0FfnZ3x1IWT_SB-EB5ET_AF.png"></image></div>
        <div><image src="http://www.yjshop.com/storage/upload/20190703/adOgSTESqPchf6T8T0FfnZ3x1IWT_SB-EB5ET_AF.png"></image></div>
        <div><image src="http://www.yjshop.com/storage/upload/20190703/adOgSTESqPchf6T8T0FfnZ3x1IWT_SB-EB5ET_AF.png"></image></div>
        <div><image src="http://www.yjshop.com/storage/upload/20190703/adOgSTESqPchf6T8T0FfnZ3x1IWT_SB-EB5ET_AF.png"></image></div>
      </div>
      <div>
        <div class="need-people">还差<span>1人</span>成团</div>
        <div class="laft-time" data-time="54656">剩余<span class="hour2">00</span>:<span class="minute2">00</span>:<span class="second2">00</span></div>
      </div>
      <div>
        <div class="pintuan-link"><a href="">去参团</a></div>
      </div>
    </div>
    <div class="rule">
      <div class="leader">规则</div>
      <div><a href="">开团/参团-邀请好友-成团发货(不成团退款)</a></div>
      <div><a href=""><icon class="iconfont icon icon-dayuhao"></icon></a></div>
    </div>
  </div>
 --> 

	<div class="goods-row5 mgb20" id="comment-detail">

		<div class="weui-cell__hd">
			<label class="cr999">评价(<?=$data['count']?>)</label>
		</div>
		<div class="weui-cell__bd" style="text-align: right;">

			<label class="">好评度<span class="crred plr10"><?=round($data['haopinglv']*100,2).'%'?></span> 

			<i class="iconfont icon-dayuhao cr999"></i></label>
		</div>
	</div>
	<!-- 店铺logo -->
	<div class="goods-shop goods-row5 mgb20" >
		<div class="weui-cell__hd shop-info" onclick="window.location.href='<?=Url::to(['/shop/index/','shop_id'=>$data['product']['shop']['id']])?>'">
			<img src="<?=$data['product']['shop']['logo']?>" alt="">
			<label ><?=$data['product']['shop']['name']?></label>
		</div>
	 	<div class="weui-cell__bd" style="display: flex;justify-content: flex-end;align-items: center;">
      <?php if(!empty($data['product']['shop']['vrlink'])): ?>
			<a target="_blank" href="<?=$data['product']['shop']['vrlink']?>" class="vr">店铺VR</a>
      <?php endif;?>
			<i onclick="window.location.href='<?=Url::to(['/shop/index/','shop_id'=>$data['product']['shop']['id']])?>'" class="iconfont icon-dayuhao cr999"></i>
		</div>
	</div>
	<div class="goods-row6">
		<div class="active">商品描述</div>
		<!-- <div class="">规格参数</div> -->
	</div>
	<div class="pro-content">
			<?=$data['product']['content']?>
	</div>
  <div style="text-align: center;margin-top: 20px;">看完啦，快去购买吧</div>
<style>
textarea{position: absolute;top:0;}
.goods-shop .weui-cell__hd img{
    width: 1rem;
    border-radius: 8px;
    float: left;
    margin-right: 20px;
}
#collection{padding: 0 .1rem 0 .3rem;}
.shop-info{display: flex;align-items: center;}
</style>
</section>
<section>
	<div class="mask" id="mask"></div>
	<div class="show-box" id="show-box">
	
		<!-- 商品规格 -->
		<div class="goods-attr-box">
			<div class="goods-attr-tit weui-flex">
				<div class="goods-attr_hd">
                    <img class="weui-media-box__thumb"						
						src="<?= isset($data['product']['image'][0])? $data['product']['image'][0]['url']:Yii::$app->params['defaultImg']['default']?>" alt="">
                </div>
				<div class="goods-attr_bd weui-flex__item">
					<input type="hidden" name="" value="1" id="goods-id">
					<h4 class="goods-attr_title"><?php echo $data['product']['name'];?></h4>
					<p class="price">￥<span class="normal"><?=$data['product']['min_price']?>-<?=$data['product']['max_price']?></span></p>
					
					<p class="kucun">库存：<?=$data['product']['stock']?></p>
					<i class="iconfont icon-guanbi" style="margin-top: -10px;"
						id="btCancel"></i>
				</div>
			</div>
			<div class="goods-attr">
				<div class="sel-attr">				
                <?php foreach ($data['attributes'] as $key=>$vo):?>
                    <h2><?=$vo['attribute_name']?></h2>
					<ul class="clearfix">
						<?php foreach ($vo['child'] as $index=>$c):?>
						<li value_id='<?=$c['value_id']?>'><?=$c['value_str']?></li>
						<?php endforeach; ?>
					</ul>
                    <?php endforeach; ?>
                </div>
				<div>
					<h2>数量</h2>
					<div class="div-num dis-box mgt20">
						<a class="num-less"></a> <input class="box-flex" type="text"
							value="1" name="number" id="goods_number"> <a class="num-plus"></a>
					</div>
				</div>
			</div>
		</div>
		<!-- 商品规格end -->
		<div class="goods-attr-box-bt">
			<a href="javascript:;" class="buy-cart">加入购物车</a> <a  href="javascript:;" class="buy-im">立即购买</a>
		</div>
	</div>
</section>
<div class="comment-main" style="display: none">
	        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:$('.comment-main').hide(200)"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    商品评价
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
        <nav class="weui-tabbar mulu-con" id="mulu-con" style="position: relative;top:.68rem;display: none">
            <a href="index.html" class="weui-tabbar__item">
                    <i class="iconfont icon-shouye"></i>
                    <p class="weui-tabbar__label">首页</p>
            </a>
            <a href="fenlei.html" class="weui-tabbar__item">
                    <i class="iconfont icon-leimupinleifenleileibie"></i>
                    <p class="weui-tabbar__label">分类</p>
            </a>
            <a href="javascript:;" class="weui-tabbar__item">
                    <i class="iconfont icon-sousuo"></i>
                    <p class="weui-tabbar__label">搜索</p>
            </a>
            <a href="cart.html" class="weui-tabbar__item">
                     <i class="iconfont icon-06"></i>
                    <p class="weui-tabbar__label">购物车</p>
            </a>
            <a href="center.html" class="weui-tabbar__item">
                    <i class="iconfont icon-renwu"></i>
                    <p class="weui-tabbar__label">我</p>
            </a>
        </nav>
        <div class="main comment-items">
			
        </div>
</div>
<script>
var skus= eval('(' + '<?= Json::encode($data['skus'],JSON_UNESCAPED_UNICODE)?>' + ')');
var productId = <?=$data['product']['product_id']?>;
var goodsArray = new Object();
<?=$data['isFavorite']==0?'var isFavorite=false;':'var isFavorite=true;'?>
var count=<?=count($data['skus'])?>;
var userType=<?php echo isset(Yii::$app->user->identity->type)?Yii::$app->user->identity->type:0?>;
var prom_type = <?= isset($data['product']['prom_type']) ? $data['product']['prom_type']:null?>
</script>
<?php
$this->registerJs(<<<JS
     $("#mulu-bt").click(function() {
        $("#mulu-more").toggle(500)
     })

    var loading = false;  //状态标记
    var page=1;
    //查看评论
    $('#comment-detail').click(function() {
        $('.comment-main').show(300);
        page=1;
        $('.comment-items').empty();
        loadlist();
    })
     //评论列表ajax
      function loadlist() {
           var html = "";
           $.ajax({
               type: "POST",
               url: "/product/comment?page="+page,
               data: {goods_id:productId },
               dataType: "json",
               beforeSend: function(){
               			loading=true;
    		          	html += '<div class="weui-loadmore">';
    					html += '<i class="weui-loading"></i>';
    					html += '<span class="weui-loadmore__tips">正在加载</span>';
    					html += '</div>';
    					$(".comment-items").append(html);
    				  },
    		  complete:function(XMLHttpRequest,textStatus){
                      // alert('远程调用成功，状态文本值：'+textStatus);
                     $(".weui-loadmore").remove();
          		 },
               error: function () {
 					        loading = false;
               },
               success: function (data) {
                        if(data.pages==0){
                   			loading=true;
                            $.toast("暂无记录", "forbidden");
               		    }else if(data.pages <= page){
                            loading=true;
                        }else{
                            loading=false;
                        }
                        for (var i = 0; i < data.items.length; i++) {
                			html += '<div class="comment-box"><div class="list-head weui-flex"><div style="width: 0.6rem;height: 0.6rem; overflow: hidden;">';
                			 if(data.items[i].member_id.avatarUrl!=null){
                            html += '<img src="'+data.items[i].member_id.avatarUrl+'">';
                        }else{
                            html += '<img src="/storage/images/avator.jpg">';
                        }
                			html += '</div><div class="weui-flex__item comment-list-title">';
                			html += '<p>'+data.items[i].member_id.username+'</p>';
                			html += '<p class="eval-xx" style="float: right;">';
                            html += total_stars(data.items[i].total_stars);
                            html += '</p></div></div><div class="list-content">';
                			html += '<p>'+data.items[i].content+'</p>';
                            if(data.items[i].image!=null){
                                  for(var j=0;j<data.items[i].image.length; j++){
                			         html += '<div ><img src="'+data.items[i].image[j]+'?imageView2/1/w/100/h/100/q/80|imageslim"></div>';
                                  }
                            }
                			html += '</div><div class="list-foot">';
                			html += '<p>【'+data.items[i].goods_id.name+'】 <span class="commemt-time">'+data.items[i].created_at+'</span></p></div></div>';
                   		}
                    $(".comment-items").append(html);
               }
           });
    	}

    //评论星星输出
    function total_stars(num){
        var v=0;
        var str="";
        while (v<num){
            str+='<i class="iconfont icon-xingxing1"></i>';
            v++;
        }
        return str;
    }
    //评论列表滚动判断
    $('.comment-main').infinite().on("infinite", function() {
            if(loading) return;
      		page++;
      		loading = true;
          	setTimeout(function() {
            loadlist();
          	}, 500);   //模拟延迟
       
    });
   

    //复制链接
    function copyText(text) {
        var textarea = document.createElement("textarea");
        var currentFocus = document.activeElement;
        document.body.appendChild(textarea);
        textarea.value = text;
        textarea.focus();
        if (textarea.setSelectionRange)
            textarea.setSelectionRange(0, textarea.value.length);
        else
            textarea.select();
        try {
            var flag = document.execCommand("copy");
        } catch(eo){
            var flag = false;
        }
        document.body.removeChild(textarea);
        currentFocus.focus();
        return flag;
    }
     $(".fenxiang").click(function() {
        var a=window.location.href;
        var flag = copyText(a);//这个必须在DOM对象的事件线程中执行
        $.toast(flag ? "复制成功！" : '"复制失败！","forbidden"');
    });
    
    //添加、 收藏
    var save_flag = true;
    $("#collection").click(function() {
          if(save_flag){
               var product_id=productId;
               if(isFavorite){
                   $.ajax({
                   type: 'post',
                   dataType:"json",
                   url: "/collection/del",
                   data: {product_id:product_id},
                   beforeSend: function(){
    		          	save_flag = false;
    				  },
                   success: function(e) {
                        if(e.status==1){
        					$.toast(e.msg);
                            $('#collection').removeClass('red');
                            isFavorite=!isFavorite;
                            save_flag = true;
    					}else{
    					   $.toast(e.msg, "forbidden");
                            save_flag = true;
    					}
                    },
                  error:function() {
                     $.toast("操作失败", "forbidden");
                    },
              	  })
                }else{
                   $.ajax({
                   type: 'post',
                   dataType:"json",
                   url: "/collection/add",
                   data: {product_id:product_id},
                   beforeSend: function(){
    		          	save_flag = false;
    				  },
                   success: function(e) {
                        if(e.status==1){
        					$.toast(e.msg);
                            $('#collection').addClass('red');
                            isFavorite=!isFavorite;
                            save_flag = true;
    					}
                        else if(e.status==2){
                              $.toast(e.msg, "forbidden");
                             location.href ="/site/login";
                        }
                        else{
    					   $.toast(e.msg, "forbidden");
                            save_flag = true;
    					}
                    },
                  error:function() {
                     $.toast("操作失败", "forbidden");
                    },
              	  })
             }
        }
    })
   $(function(){
		      
        if(count==1){
           
              $(".sel-attr ul li").trigger("click");
        }
        initStock();  
	}); 	
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationType: 'fraction',
        paginationClickable: true
    });
 
    $("#mulu-bt").click(function() {
        var mulu = $(".goods-nav");
        if (mulu.is(":hidden")) {
            mulu.show();
        } else {
            mulu.hide();
        }
    });
        var showBox = $('#show-box');
        var mask = $('#mask');

        function hideActionSheet() {
            showBox.removeClass('weui-actionsheet_toggle');
            mask.fadeOut(200);
        }

        mask.on('click', hideActionSheet);
        $('#btCancel').on('click', hideActionSheet);
        $(".btShow").on("click", function() {
            showBox.addClass('weui-actionsheet_toggle');
            mask.fadeIn(200);
        });
    //购物车物品数量
    var MAX,
        MIN = 1;
    $('.num-less').click(function(e) {
        var input = $(e.currentTarget).parent().find('#goods_number');
        var number = parseInt(input.val() || "0") - 1;
        if (number < MIN) number = MIN;
        input.val(number);
        goodsAttr();

    })
    $('.num-plus').click(function(e) {
        var input = $(e.currentTarget).parent().find('#goods_number');
        var number = parseInt(input.val() || "0") + 1;
        input.val(number);
        goodsAttr();
    })
    
    $("#goods_number").on(" input propertychange",function(){
      goodsAttr();
    })
    
    
		
	
	//初始化规格库存disabled值
	function initStock(){
		var stockSkuIds = new Array();//含库存的sku_id数组
		var stockValueMap = new Map();//有库存的value_id的map		
		var valueIds = new Map();//所有的value_id的map
		var values = new Array();//所有的value_id的map
		//所有的value_id的map
		$(".sel-attr ul li").each(function(){
			valueIds.set($(this).attr('value_id'),$(this).attr('value_id'));	
			values.push($(this).attr('value_id'));	
		});
		//获取含库存的sku_id数组
		$(skus).each(function(){
			var valueStr = this.sku_id.substring(this.sku_id.indexOf('_')+1);
			if(this.stock>0){
				stockSkuIds.push(valueStr);
			}						
		});
		//获取有库存的value_id的map
		$(stockSkuIds).each(function(){		
			var skuIdArray = this.split('_');
			$(skuIdArray).each(function(){
				if(!stockValueMap.has(this.toString())){
					stockValueMap.set(this.toString(),this.toString());		
				}					 
			});
		});	
		
		var reg = '';//获取每一行选择的value正则格式\d_\d_\d。。。。
		$(".sel-attr ul").each(function(){
			var activeValue = $(this).find(".active");
         
			if(activeValue.length==0){				
				reg +="\\\"+"d+"+'_';
			}else{
				reg += activeValue.eq(0).attr('value_id')+'_'; 				
			}
		});	
		var emptyValueMap = new Map();
		
		reg = reg.substring(0,reg.length-1);
		test(reg,values,emptyValueMap,0);
		//console.log(emptyValueMap);
		$(".sel-attr ul li").each(function(){					
			if(emptyValueMap.has($(this).attr('value_id'))){
				$(this).addClass("disabled");
			}else{
				$(this).removeClass("disabled");	
			}			
		});		
	}
		/**递归取差集
		 *  reg 获取每一行选择的value正则格式\d+_\d+_\d+。。。。
		 *  i 为规格值对应的行号
		 *	emptyValueMap 空的emptyValueMap
		 *  values 所有的规格值id数组 
		 */
		function test(reg,values,emptyValueMap,i){
			if(i>reg.length){
				return;
			}
			var regValueIds = reg.split('_');
			var newReg = '^';
			if(!$.isNumeric(regValueIds[i])){				
				newReg += reg;
			}else{
				for(var k=0;k<regValueIds.length;k++){
					i==k? newReg += "\\\"+"d+"+'_': newReg +=regValueIds[k]+'_'; 
				}
				newReg = newReg.substring(0,newReg.length-1);
			} 
			i++;			
			var skuIds = new Array();//匹配选择的规格的并且有库存的数组	
			//赋值库存的和筛选的数组
			$(skus).each(function(){
				var valueStr = this.sku_id.substring(this.sku_id.indexOf('_')+1);	
                //console.log(valueStr.match(newReg));		
				if(valueStr.match(newReg)!=null&&this.stock>0){
					skuIds.push(valueStr);
				}			
			});
			var stockValueMap = new Map();//匹配选择的规格的并且有库存的value_id的map
			$(skuIds).each(function(){		
				var skuIdArray = this.split('_');
				$(skuIdArray).each(function(){
					if(!stockValueMap.has(this.toString())){
						stockValueMap.set(this.toString(),this.toString());		
					}					 
				});
			});
			var li = $('.sel-attr ul').eq(i-1).find('li');
			var lineValues = new Array();
			$(li).each(function(){
				lineValues.push($(this).attr('value_id'));
			});
			//console.log(lineValues);	
			//赋值无库存不可选规格值
			$(values).each(function(){
				//获取当前规格行号，查看当前遍历的value_id是否在同一行，如果是则不进行操作	
				if(lineValues.indexOf(this.toString())==-1){
					return true;
				}
				if(!stockValueMap.has(this.toString())){
					emptyValueMap.set(this.toString(),this.toString());
				}
			});	
			//console.log(skuIds);	
			//console.log(stockValueMap);		
			return test(reg,values,emptyValueMap,i);
		}	
	
	
	//规格点击事件
    $(".sel-attr ul li").click(function() {
		
		
		//如果选中的是disable的按钮，不做操作
        if($(this).hasClass('disabled')){
            return;
        }
		
        //设置选中状态
        updateAttrStatus($(this));  
		initStock(); 
		   
        //设置无库存不可选属性
        updateUnableAttr($(this)); 
        //设置公共信息     
        updateAttrCommon($(this));
       
    })
    //设置选中状态
    function updateAttrStatus(attr){
        if(attr.prop("className")=='active'){
            attr.removeClass("active");
        }else{
           attr.addClass("active");
        }
        attr.siblings("li").removeClass("active");
    }
    //设置无库存不可选属性
    function updateUnableAttr(attr){
        //获取每行选中的attr的id；
        var selectedAttrs = $("li.active");        
        //拼接attr_id，并拼接成正则
        var reg = "1";
        $(selectedAttrs).each(function(){
             reg += '_'+$(this).attr('value_id');
        }); 
        //console.log(reg);
        //循环attr_id组装的正则匹配所有的sku_id

    }
    var prom_id = null;
    //设置公共信息    
    function updateAttrCommon(attr){
        var selectedAttrs = $("li.active");        
        if(selectedAttrs.length == $("div.sel-attr").find("ul").length){
            var skuId = productId;
            
            $(selectedAttrs).each(function(){
                 skuId += '_'+$(this).attr('value_id');
            }); 
            $(skus).each(function(){
                if(this.sku_id==skuId){
                    //重置
                    prom_id = null;
                    //console.log('eq');
                    //设置sku背景图
                    if(this.thumbImg!=null){
                        
                    }
                    if(this.prom!=null&&this.prom.proming_status==1){
                        this.stock = this.prom.goods_num-this.prom.order_num;
                        prom_id = this.prom.id;
                    }
                    //设置库存
                    $("div.goods-attr_bd p.kucun").text("库存："+this.stock);
                    //设置价格
                    if(this.prom!=null&&this.prom.proming_status==1){      
                        $("div.goods-attr_bd p.price").text("秒杀：￥"+this.prom.price);                        
                    }else if(userType==3&&this.plus_price>0){
                        $("div.goods-attr_bd p.price").text("村点价格：￥"+this.plus_price);
                    }else{
                        $("div.goods-attr_bd p.price").text("￥"+this.sale_price);
                    }
                }
                goodsArray.sku_id = skuId ;
            }); 
            goodsAttr();
        }else{
			//设置sku背景图
                   
           	//设置库存
           	$("div.goods-attr_bd p.kucun").text("库存："+$('#goods_stock').attr('data'));
        	//设置价格
			var maxPrice = $('#goods_price').attr('max');
			var minPrice = $('#goods_price').attr('min');
			$("div.goods-attr_bd p.price").text("￥"+minPrice+'-'+maxPrice);
			$("#goodsAttr").html('请选择规格');      
		}       
    }

    

    function goodsAttr() {
        var index;
        var goodsColor = "";
        $(".sel-attr ul li").each(function() {
            if ($(this).is('.active')){
                index = $(this).index();
                goodsColor += $(this).html()+',';
            }                
        });
        //var goodsColor = $(".sel-attr ul li").eq(index).html();
        var goodsNumber = $("#goods_number").val();
        var goodsAttr = goodsColor  + goodsNumber;
        $("#goodsAttr").html(goodsAttr);       
        goodsArray.num = goodsNumber;
        //var data=JSON.stringify(goodsArray);
        //console.log(data);
    }

   $(".buy-cart").click(function() {
		var flag = true;
        $(".sel-attr ul").each(function() {
			var li = $(this).children().filter('li.active');			
			if(!li.length>0){
				var msg = '请选择'+$(this).prev().text();
				$.toast.prototype.defaults.duration = 800;
				$.toast(msg, "forbidden");
				flag = false;
				return true;
			}                           
        });
		if(!flag){
			return;
		}
        
        $.ajax({
            type: "POST",
            url: "/cart/add",
            data: {sku_id:goodsArray.sku_id,num:goodsArray.num,prom_id:prom_id,prom_type:prom_type},
			dataType:'json',
			async:false,
            success: function(data) {
              if(data.status==1)
              {
                 $.toast(data.msg, "success"); 
				 showBox.removeClass('weui-actionsheet_toggle');
           		 mask.fadeOut(200);
                
                 $('#cart-num').html(data.num);                 
              }
              else
              {
                $.toast(data.msg, "forbidden"); 
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                /*错误信息处理*/
                if(jqXHR.status==403){
                    $.toast.prototype.defaults.duration = 2000;
                    $.toast('请先登录', "forbidden");
                    $(window).attr('location','/site/login');
                }
                
            }
            
        });


    });

    //立即购买
    $(".buy-im").click(function() {
        var target_url = SITE_URL+"/cart/confirm?type=1&id=";
		var flag = true;
        $(".sel-attr ul").each(function() {
			var li = $(this).children().filter('li.active');			
			if(!li.length>0){
				//console.log(li.size()+'1111');
				var msg = '请选择'+$(this).prev().text();
				$.toast.prototype.defaults.duration = 800;
				$.toast(msg, "forbidden");
				flag = false;
				return true;
			}                           
        });
		if(!flag){
			return;
		}
        $.ajax({
            type: "POST",
            url:  "/cart/add",
            data: {sku_id:goodsArray.sku_id,num:goodsArray.num,type:1,prom_id:prom_id,prom_type:prom_type},
			dataType:'json',
			async:false,
            success: function(data) {
              if(data.status==1)
              {
                 
                 window.location.href = target_url+data.msg;
              }
              else
              {
                $.toast(data.msg, "forbidden"); 
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                /*错误信息处理*/
                if(jqXHR.status==403){
                    $.toast.prototype.defaults.duration = 2000;
                    $.toast('请先登录', "forbidden");
                      $(window).attr('location', SITE_URL+'/site/login');
                }
                
            }
            
        });
    });

    /*倒计时*/
    var pintuan_time = parseInt(60);//倒计时总秒数量
    function timer(intDiff,index){
       var interval = window.setInterval(function(){
          var day=0,
              hour=0,
              minute=0,
              second=0;//时间默认值        
          if(intDiff > 0){
              day = Math.floor(intDiff / (60 * 60 * 24));
              hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
              minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
              second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
          }
          else{
            clearInterval(interval);
          }
          if (hour <= 9) hour = '0' + hour;
          if (minute <= 9) minute = '0' + minute;
          if (second <= 9) second = '0' + second;
          if(index == 0){
            $('#day').text(day);
            $('#hour').text(hour);
            $('#minute').text(minute);
            $('#second').text(second);
          }else{
            //$('.day'+index).text(day);
            $('.hour'+index).text(hour);
            $('.minute'+index).text(minute);
            $('.second'+index).text(second);
          } 
          intDiff--;
        }, 1000);
    } 
    timer(pintuan_time,0);
    //已开团的倒计时
    $(".laft-time").each(function(index,element){
      var time = $(this).data('time');
      timer(time,index+1);
    });
JS
);
?>
<?php $this->beginBlock('block1') ?>
<?php if(isset($jssdk)):?>
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '<?=$jssdk['appId']?>', // 必填，公众号的唯一标识
        timestamp: '<?=$jssdk['timestamp']?>' , // 必填，生成签名的时间戳
        nonceStr: '<?=$jssdk['nonceStr']?>', // 必填，生成签名的随机串
        signature: '<?=$jssdk['signature']?>',// 必填，签名
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] // 必填，需要使用的JS接口列表
        });
     wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
     	//分享给朋友和QQ
/*	       wx.updateAppMessageShareData({ 
	            title: '$name', // 分享标题
	            desc: '$name', // 分享描述
	            link: '$url', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
	            imgUrl: '$img', // 分享图标
	            success: function () {
	              // 设置成功
	            }
	         });
	   //分享给朋友圈和QQ空间
	   wx.updateTimelineShareData({ 
	            title: '$name', // 分享标题
	            link: '$url', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
	            imgUrl: '$img', // 分享图标
	            success: function () {
	              // 设置成功
	            }
	    });*/
	    wx.onMenuShareTimeline({
	         title: '<?=$data['product']['name']?>', // 分享标题
	          link: '<?=$jssdk['url']?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
	          imgUrl: '<?php  echo isset($data['product']['image'][0]['thumbImg'])?$data['product']['image'][0]['thumbImg']:'';?>', // 分享图标
		    success: function () {
		    // 用户点击了分享后执行的回调函数
			}
	});
		wx.onMenuShareAppMessage({
		 title: '<?=$data['product']['name']?>', // 分享标题
	            desc: '<?=$data['product']['name']?>', // 分享描述
	            link: '<?=$jssdk['url']?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
	            imgUrl: '<?php  echo isset($data['product']['image'][0]['thumbImg'])?$data['product']['image'][0]['thumbImg']:'';?>', // 分享图标
		type: '', // 分享类型,music、video或link，不填默认为link
		dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
		success: function () {
		// 用户点击了分享后执行的回调函数
		}
		});
	   
    });

    wx.error(function(res){
        // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
    	});
    	<?php endif;?>
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>  