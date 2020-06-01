<?php
use yii\helpers\Url;
?>
<style type="text/css">
  .list-col-2{margin: 0 2%;}
  .no-product .iconfont{font-size: 50px;}
  .shop-info{display: flex;align-items: center;margin-top: 10px;justify-content: space-between;}
  .shop-logo{width: 40px;margin-right: 10px;}
  .shop-info a{color: #000;}
  .shop-left{display: flex;align-items: center;}
  .collection{color: #04BE02;border:1px solid #04BE02;border-radius: 30px;padding: 3px 10px;}
  .collected{background-color: #ff4444;color: #fff;border-radius: 30px;padding: 3px 10px;}
  .sale_num{color: #999 !important;}
</style>
<header class="top-fixed">
	<div class="weui-flex top-box">
		<div onclick="javascript:history.back(-1);">
			<i class="iconfont icon-fanhui"></i>
		</div>
		<div class="weui-flex__item">
			<div class="weui-flex" >
				<div class="weui-flex__item"><?=$shop['name']?></div>
			</div>
		</div>
		<div class="weui-flex mgr20">
			<!-- <i id='collection' class="iconfont icon-redshoucang weui-flex__item <?=$isFavorite==0?'':'red'?>"></i> -->
			<!-- <i class="iconfont icon-fenxiang weui-flex__item fengxiang"></i> -->
			<i class="iconfont icon-mulu1 weui-flex__item" id="mulu-bt"></i>
		</div>
	
	</div>
</header>
 <aside class="goods-nav hide">
            <ul>
                <li><a href="<?=Url::to(['site/index'])?>"><i class="iconfont icon-shouye"></i>首页</a></li>
<!--                  <li><a href=""><i class="iconfont icon-sousuo"></i>搜索</a></li>  -->
                <li><a href="<?=Url::to(['member/index'])?>"><i class="iconfont icon-renwu"></i>个人中心</a></li>
                <li><a href="<?=Url::to(['order/all'])?>"><i class="iconfont icon-dingdan"></i>全部订单</a></li>
            </ul>
        </aside> 
        <!-- 搜索 -->
        <div class="search-fupin shop-search">
            <div class="search-btn-wrap">
                <i class="ico-search"></i>
                <input type="search" class="textarea" placeholder="搜索您需要的产品">
                <div class="mask1"></div>
            </div>
            <div class="shop-info">

                <a href="<?=Url::to(['shop/detail','shop_id'=>yii::$app->request->get('shop_id')])?>">
                  <div class="shop-left">
                    <div><img class="shop-logo" src="<?=$shop['logo']?>" /></div>
                    <div class="weui-flex__item"><?=$shop['name']?></div>
                  </div>
                </a>
                <div>
                  <span id='collection' class="<?=$isFavorite==0 ? 'collection':'collected'?>"><?=$isFavorite==0 ? '收藏':'已收藏'?></span>
                </div>
            </div>
        </div>

        <!-- 搜索end -->
        <!-- 轮播图 -->
        <div class="swiper-container index-ban">
            <div class="swiper-wrapper">
            <?php foreach ($carousels as $v):?>
                <div class="swiper-slide">
                	<a href="<?=$v['url']?>">
                    <img src="<?=$v['image']?>" alt="" class="block">
                    </a>
                </div>
                <?php endforeach;?>
<!--                 <div class="swiper-slide"> -->
<!--                     <img src="/storage/images/banner1.png" alt="" class="block"> -->
<!--                 </div> -->
<!--                 <div class="swiper-slide"> -->
<!--                     <img src="/storage/images/banner.png" alt="" class="block"> -->
<!--                 </div> -->
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        <!-- 轮播图结束 -->


    <div class="paixun-box">
        <!-- 搜索end -->
        <div class="active" id="-hot,-up_time">综合<span class="iconfont icon-zelvxuanzefeiyongdaosanjiaoxingfandui"></span></div>
        <div id="sale">销量<span class="iconfont icon-zelvxuanzefeiyongdaosanjiaoxingfandui"></span></div>
        <div id="min_price">价格<span class="iconfont icon-zelvxuanzefeiyongdaosanjiaoxingfandui"></span></div>
        <span class="iconfont icon-202 list-btn"></span>
    </div>
        <div id="list" class="list mgb120">
            <div class="list-col-1">
                <ul class="weui-row" id="weui-row">
                <?php foreach ($products as $product):?>
                   <li class="weui-col-50">
                        <a href="<?=Url::to(['product/detail','id'=>$product['product_id']])?>">
                            <div class="item-pic"><img src="<?=
                            count($product['image'])>0?$product['image'][0]['url'].'?imageView2/1/w/160/h/160':Yii::$app->params['defaultImg']['default']?>" alt="">
                            </div>
                            <div class="item-txt">
                                <p class="name"><?=$product['name']?></p>
                                <p class="price">￥<?=$product['min_price']?></p>
                                <div class="price-line">
                                <span >￥<?=$product['min_price']?></span>
                           		 </div>
								 <div class="donation">
                                 <?php if(!empty($product['shop']['percent'])&&$product['shop']['is_village']==1):?>
                                   <p>捐赠<?=$product['shop']['percent']*100?>%扶贫基金</p> 
                                 <?php endif;?>
                                 </div>
								<div class="item-txt-btm">
                                  <div> 
                                 <?php if(!empty($product['shop']['percent'])&&$product['shop']['is_village']==1):?>
                                   <p>捐赠<?=$product['shop']['percent']*100?>%扶贫基金</p> 
                                 <?php endif;?>

                                  </div>  

                                  <span class="buy">立即购买</span>


                                </div>
                             </div>
                          </a>                    
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
            <?php if(count($products) == 0):?>
            <div class="no-product null-data"><div class="iconfont icon-empty"></div><div>暂无商品,去逛逛其他的吧</div><div class="gohome"><a href="<?=Url::to(['product/index'])?>">去逛逛</a></div></div>
            <?php endif;?>   
            <?php   if($pagecount==1):?>
                <div class="none" style="display: block;">亲，没有更多了~</div>
            <?php else :?>
            <div class="weui-infinite-scroll loading">
              <div class="infinite-preloader"></div>
              <span class="iconfont icon-tupianzhengzaijiazai"></span>
             	  正在加载... 
            </div>
            <div class="none" >亲，没有更多了~</div>
            <?php endif;?>
        </div>
<?php $this->beginBlock('block1') ?>  
     $("#mulu-bt").click(function() {
        $("#mulu-more").toggle(500)
     })
		var is_distribut='<?=yii::$app->session->get('is_distribut')?>';
		var pid='<?=Yii::$app->user->id?>';
		var defaultImg="<?=Yii::$app->params['defaultImg']['default']?>";
		//收藏店铺
		<?=$isFavorite==0?'var isFavorite=false;':'var isFavorite=true;'?>
		var shop_id=<?=$shop['id']?>;
		var save_flag = true;
   		 $("#collection").click(function() {
          if(save_flag){
               if(isFavorite){
                   $.ajax({
                   type: 'post',
                   dataType:"json",
                   url: "/collection/del-shop",
                   data: {shop_id:shop_id},
                   beforeSend: function(){
    		          	save_flag = false;
    				  },
                   success: function(e) {
                        if(e.status==1){
        					$.toast(e.msg);
                            $('#collection').removeClass('collected');
                            $('#collection').addClass('collection');
                            $('#collection').text('收藏');
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
                   url: "/collection/add-shop",
                   data: {shop_id:shop_id},
                   beforeSend: function(){
    		          	save_flag = false;
    				  },
                   success: function(e) {
                        if(e.status==1){
        					$.toast(e.msg);
                            $('#collection').addClass('collected');
                            $('#collection').removeClass('collection');
                            $('#collection').text('已收藏');
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

        function GetQueryString(name)
        {
             var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
             var r = window.location.search.substr(1).match(reg);//search,查询？后面的参数，并匹配正则
             if(r!=null)return  unescape(r[2]); return null;
        }
       var condition= $('.active').attr('id');
       var shopId = GetQueryString('shop_id');
      // 滚动加载
       var pages = 1;
       var sizes = 10;
       var loading = false;  //状态标记
       var count = 2;
       
       $('.paixun-box>div').click(function(){
       $(this).addClass('active').siblings().removeClass('active');
       var isActive = $(this).hasClass('active');
       var isDown = $(this).children("span").hasClass('icon-zelvxuanzefeiyongdaosanjiaoxingfandui');
       var isUp = $(this).children("span").hasClass('icon-zelvxuanzefeiyongzhengsanjiaoxingzhichi');
        if(isActive){
            if(isDown){
                $(this).children("span").removeClass("icon-zelvxuanzefeiyongdaosanjiaoxingfandui").addClass("icon-zelvxuanzefeiyongzhengsanjiaoxingzhichi")
                var i=2
                 i%2==1?condition='-'+$('.active').attr('id'):condition=$('.active').attr('id');

            }else if(isUp){
                $(this).children("span").removeClass("icon-zelvxuanzefeiyongzhengsanjiaoxingzhichi").addClass("icon-zelvxuanzefeiyongdaosanjiaoxingfandui")
                var i=1
                i%2==1?condition='-'+$('.active').attr('id'):condition=$('.active').attr('id');

            }
        }
        $("#weui-row").html('');
        pages=1;
        loadlist();  
    })


    $(document.body).infinite().on("infinite", function() {
    
      if(loading) return;
      loading = true;
      pages++; //页数
      
      if(pages<=count){
          loadlist();          
      }else if(count>=1){
         $(".none").show();
         loading = true;        
      }
        
        
    }
);

      function loadlist() {
      	   $(".none").hide();
      	   $(".loading").show();
           var html = "";
           $.ajax({
               type: "POST",
               url: "/api/v1/product/index?shop_id=<?=$shop['id']?>&sort="+condition+"&page="+pages,
               data: {'num': sizes,'shop_id':shopId,'name':name },
               dataType: "json",
               error: function (request) {

                   $(".loading").hide();
                   $.hideLoading();         
                   html += '<div class="no-product"><div class="iconfont icon-empty"></div><div>暂无商家~</div></div>';



                   $("#weui-row").append(html);
               },
               success: function (data) {
                    loading = false;
                    count = data._meta.pageCount;
                    if(data._meta.pageCount==0){
                        $(".loading").hide();
                        html += '<div class="no-product"><div class="iconfont icon-empty"></div><div>暂无商家~</div></div>';


                        $("#weui-row").append(html);
                        return;
                    }else if(data._meta.pageCount==1){
                        $(".loading").hide();
                    }
                       for (var i = 0; i < data.items.length; i++) {
                          html += '<li class="weui-col-50">';
                           if(is_distribut==true){
                                html += '<a href="/product/detail?id=' + data.items[i].product_id + '&pid='+pid+'">';
                            }else{
                                 html += '<a href="/product/detail?id='+data.items[i].product_id+'" >';
                            }
                          if(data.items[i].image.length>0)
                           {
                          html += '<div class="item-pic"><img src="'+ data.items[i].image[0].thumbImg +'" alt=""></div>';
                          }else{
                          	html += '<div class="item-pic"><img src="'+ defaultImg +'" alt=""></div>';
                          }
                          html += '<div class="item-txt">';
                          html += '<p class="name">'+ data.items[i].name +'</p>';
                          html += '<p class="price">￥'+ data.items[i].min_price +'</p>';
                          html += '<div class="price-line">';
                          html += '<span >￥'+ data.items[i].min_price +'</span>';
                          
                          html += '</div>';

                         if(typeof(data.items[i].percent)!= 'undefined' && data.items[i].percent > 0 && data.items[i].fupin==1){
                           html += '<div class="donation"><p>捐赠'+data.items[i].percent+'%的扶贫基金</p></div>';
                       }
                      html += '<div class="item-txt-btm">';
                       if(typeof(data.items[i].percent)!= 'undefined' && data.items[i].percent > 0 && data.items[i].fupin==1){
                           html += '<div><p>捐赠'+data.items[i].percent+'的扶贫基金</p></div>';
                       }
                      html += '<div>';
                    

                          html += '</div>';
                          html += '<span class="buy">立即购买</span>';
                          html += '</div>';
                          html += '</div>';
                          html += '</a>';
                          html += '</li>';
                       }
                       $("#weui-row").append(html);
                       $(".loading").hide();
                       
               }
           });

   }
       $(".list-btn").click(function(){
      var that = $("#list").children("div")
      if(that.hasClass("list-col-2")){
        $(".list-btn").removeClass("icon-leimupinleifenleileibie").addClass("icon-202");
        that.removeClass("list-col-2").addClass("list-col-1");
      }else{
        $(".list-btn").removeClass("icon-202").addClass("icon-leimupinleifenleileibie");
        that.removeClass("list-col-1").addClass("list-col-2");
      }
    })

    
    //搜索功能
    $(".ico-search").click(function(){
            name=$('.textarea').val();
            $("#weui-row").html('');
            pages=1;
            loadlist();
    })
    $('.textarea').keyup(function(evt){
        if (evt.keyCode == 13) {
             $('.textarea').blur();
            name=$('.textarea').val();
            $("#weui-row").html('');
            pages=1;
            loadlist();

        }   
    });
    
    $(".fenxiang").click(function() {
        var a=window.location.href;
        var flag = copyText(a);//这个必须在DOM对象的事件线程中执行
        $.toast(flag ? "复制成功！" : '"复制失败！","forbidden"');
    });
    $("#mulu-bt").click(function() {
        var mulu = $(".goods-nav");
        if (mulu.is(":hidden")) {
            mulu.show();
        } else {
            mulu.hide();
        }
    });


<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>  
