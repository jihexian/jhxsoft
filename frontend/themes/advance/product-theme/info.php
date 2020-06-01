<?php
use yii\helpers\Url;
?>

 <div class="index-ban theme_info">
          
   <img src="<?=$theme['image']?>" alt="" class="block">

 </div>      
 <style>
.theme_info {
padding: 0.2rem 0rem;
background-color:#fff;

}
.no-product{display: none;}
</style>
<div id="list" class="list ">
    <div class="list-col-2 goods-box">
        <ul class="weui-row" id="list-items">
            <?php if(!empty($products)):?>
                <?php foreach ($products as $product):?>
                    <li class="weui-col-50">
                        <a href="<?=Url::to(['product/detail','id'=>$product['product_id']])?>">
                            <div class="item-pic"><img src="<?=
                            count($product['image'])>0?$product['image'][0]['url']:Yii::$app->params['defaultImg']['default']?>" alt="">
                            </div>
                            <div class="item-txt">
                                <p class="name"><?=$product['name']?></p>
                                <p class="price">￥<?=$product['min_price']?></p>
                                <div class="price-line">
                                <span >￥<?=$product['min_price']?></span>
                           		 </div>
							 <div class="donation">
                                   <?php if(!empty($product['shop']['percent'])):?>
                                   <p>捐赠<?=$product['shop']['percent']*100?>%扶贫基金</p> 
                                 <?php endif;?>
                                 </div>
								<div class="item-txt-btm">

                                  <div> 
                                 <?php if(!empty($product['shop']['percent'])):?>
                                   <p>捐赠<?=$product['shop']['percent']*100?>%扶贫基金</p> 
                                 <?php endif;?>
                                  </div>  

                                  <span class="buy">立即购买</span>


                                </div>
                            </div>
                        </a>                    
                    </li>
                <?php endforeach;?>
              <?php else: ?>
              <style>.no-product{display: block;}</style>
              <?php endif;?>
          </ul>
    </div>
    <div class="no-product"><div class="iconfont icon-empty"></div><div>暂无商品,去逛逛其他的吧</div><div><a class="gohome" href="<?=Url::to(['product/index'])?>">去逛逛</a></div></div>
    <div class="weui-infinite-scroll loading">
      <div class="infinite-preloader"></div>
      <span class="iconfont icon-tupianzhengzaijiazai"></span>
       正在加载... 
    </div>
    <div class="none">亲，没有更多了~</div>
</div>
<script>
var defaultImg="<?=Yii::$app->params['defaultImg']['default']?>";
var name="<?=Yii::$app->request->get('name','')?>";
var shop_id="<?=Yii::$app->request->get('shop_id','')?>";
var cat_id="<?=Yii::$app->request->get('cat_id','')?>";
var type_id="<?=Yii::$app->request->get('type_id','')?>";
var theme_id="<?=$theme['id']?>";
var is_distribut='<?=yii::$app->session->get('is_distribut')?>';
var pid='<?=Yii::$app->user->id?>';
</script>
<?php
$this->registerJs(<<<JS
       $('.screen-box>div').click(function(){
            index=$(this).index();
            $('.screen-list').children('div').hide();
            $('.screen-list').children('div:eq('+index+')').show();
       });
       //console.log(defaultImg);
       var condition= $('.active').attr('id');
       var pages = 1;
       var sizes = 10;
       var loading = false;  //状态标记
       var count = 2;
       $('.paixun-box>div').click(function(){
       $(this).addClass('active').siblings().removeClass('active');
       var isActive = $(this).hasClass('active');
       var isDown = $(this).children("span").hasClass('icon-zelvxuanzefeiyongdaosanjiaoxingfandui');
       var isUp = $(this).children("span").hasClass('icon-zelvxuanzefeiyongzhengsanjiaoxingzhichi');
       $(".none").hide();
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
        $("#list-items").html('');
        pages=1;
        loadlist();  
    })

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

    $(document.body).infinite().on("infinite", function() {
      if(loading) return;
      pages++; //页数
      if(pages <= count){
        $(".loading").show();
        loadlist();          
      }else if(pages>count){
         $(".none").show();
         $(".loading").hide();
         loading = true;        
      }
    });

    function loadlist() {
           loading = true;
           var html = "";
           $.ajax({
               type: "POST",
               url: "/api/v1/product/index?sort="+condition+"&page="+pages,
               data: {'num': sizes,'name':name,'shop_id':shop_id,'cat_id':cat_id,'type_id':type_id,'theme_id':theme_id},
               dataType: "json",
               error: function (request) {
                   $(".loading").hide();
                   $.hideLoading();         
                   $(".no-product").show();
               },
               success: function (data) {
                    count = data._meta.pageCount;
                    if(count==0){
                        $.hideLoading();
                        $(".loading").hide();
                        $(".no-product").show();
                        return;
                    }
                    $(".no-product").hide();
                    for (var i = 0,len = data.items.length; i < len; i++) {
                      html += '<li class="weui-col-50">';
                        if(is_distribut==true){
                            html += '<a href="/product/detail?id=' + data.items[i].product_id + '&pid='+pid+'">';
                        }else{
                             html += '<a href="/product/detail?id=' + data.items[i].product_id + '">';
                        }
                      html += '<div class="item-pic">'
                        if(data.items[i].image.length>0)
                        {
                            html += '<img src="'+ data.items[i].image[0].thumbImg +'" alt="">';
                        }else{
                            html += '<img src="'+ defaultImg +'" alt="">';
                        }

                      html += '</div>';
                      html += '<div class="item-txt">';
                      html += '<p class="name">'+ data.items[i].name +'</p>';
                      html += '<p class="price">￥'+ data.items[i].min_price +'</p>';
                      html += '<div class="price-line">';
                           html += '<span>￥'+ data.items[i].min_price +'</span>';
                      html += '</div>';
                     if(typeof(data.items[i].percent)!= 'undefined' && data.items[i].percent > 0){
                           html += '<div class="donation"><p>捐赠'+data.items[i].percent+'的扶贫基金</p></div>';
                       }
                      html += '<div class="item-txt-btm">';
                       if(typeof(data.items[i].percent)!= 'undefined' && data.items[i].percent > 0){
                           html += '<div><p>捐赠'+data.items[i].percent+'%的扶贫基金</p></div>';
                       }
                      html += '<div>';
                
                      html += '</div>';
                      html += '<span class="buy">立即购买</span>';
                      html += '</div>';
                      html += '</div>';
                      html += '</a>';
                      html += '</li>';
                    }
                    $("#list-items").append(html);
               },
               complete:function(){
                  $.hideLoading();
                  $(".loading").hide();
                  loading = false;
               }
           });
    }
    
    //搜索功能
    $(".ico-search").click(function(){
            name=$('.textarea').val();
            $("#list-items").html('');
            pages=1;
            loadlist();
    })
    $('.textarea').keyup(function(evt){
      if (evt.keyCode == 13) {
          name=$('.textarea').val();
          $("#list-items").html('');
          pages=1;
          loadlist();
      }
    });

JS
);
?>


