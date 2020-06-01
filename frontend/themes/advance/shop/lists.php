<?php
use yii\helpers\Url;
?>
<style type="text/css">
  .no-product .iconfont{font-size: 50px !important;color: #999;margin-bottom: 10px;}
  .item-r{position: relative;}
  .go-vr{
    font-size: .30rem;
    color: #fff;
    background-color: #3385ff;
    border-radius: 5px;
    padding: .13rem .3rem;
    margin: .2rem .2rem 0 0;
  }
</style>
        <!-- 搜索 -->
<div class="search-fupin">
	<div class="search-btn-wrap">
		<i class="ico-search"></i>
		<input type="search" class="textarea search" placeholder="<?=Yii::$app->request->get('name','请输入您要查找的店铺')?>">
		<div class="mask1"></div>
	</div>
</div>
  <!-- <div class="paixun-box">
        <div class="active" id="sort,money,-created_at">综合<span class="iconfont icon-zelvxuanzefeiyongdaosanjiaoxingfandui"></span></div>
        <div id="village_id"><span class="iconfont icon-zelvxuanzefeiyongdaosanjiaoxingfandui">扶贫</span></div>
        <div id="id"><span class="iconfont icon-zelvxuanzefeiyongdaosanjiaoxingfandui">id</span></div>
        <span class="iconfont icon-leimupinleifenleileibie list-btn"></span>
    </div>
  -->
<!-- 搜索end -->

<!-- 扶贫商家 -->
<div class="list mgb120 fupinseller-list">
	<div class="list-col-1" id="">
		<ul class="weui-row" id="weui-row">
		<?php foreach ($model as $v):?>
			<li class="list-col1-item" >
				<div class="item-pic" onclick="window.location.href=<?="'".Url::to(['/shop/index','shop_id'=>$v['id']])."'" ?>">
					<img src="<?=$v['logo']?>" alt="">
				</div>
				<div class="item-r" >
          <div>
  					<p class="name" onclick="window.location.href=<?="'".Url::to(['/shop/index','shop_id'=>$v['id']])."'" ?>"><?=$v['name']?></p>
  					<div class="shop" onclick="window.location.href=<?="'".Url::to(['/shop/index','shop_id'=>$v['id']])."'" ?>">
  						<p class="shop-addr"><?=$v['address']?></p>
  						<p class="shop-tel"><?=empty($v['tel'])?'暂无电话':$v['tel']?></p>
  					</div>
            <div style="display: flex;">
              <?php if(!empty($v['vrlink'])): ?>
              <a target="_blank" href="<?=$v['vrlink']?>"><div class="go-vr">店铺VR</div></a>
              <?php endif;?>
              <div class="go-shop" onclick="window.location.href=<?="'".Url::to(['/shop/index','shop_id'=>$v['id']])."'" ?>">进店</div>
            </div>
          </div>
         
				</div>
        
			</li>
			<?php endforeach;?>
		</ul>
	</div>
</div>


<?php $this->beginBlock('shop_list') ?>  
   // 滚动加载
   var pages = 1;
   var sizes = 10;
   var loading = false;  //状态标记
   var count = 2;
   var condition= $('.active').attr('id');
  
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
   var name="<?=Yii::$app->request->get('name','')?>";
	
    $(document.body).infinite().on("infinite", function() {
      if(loading) return;
      loading = true;
      pages++; //页数
      setTimeout(function() {
        if(pages<=count){
            loadlist();          
        }else if(count>=1){
           $(".none").show();
           loading = true;        
        }
        loading = false;
      }, 1500);   //模拟延迟
    });

      function loadlist() {

           var html = "";
           $.ajax({
               type: "POST",
                  url: "/api/v1/shop/index?sort="+condition+"&page="+pages,
               data: {'num': sizes,'name':name},
               dataType: "json",
                beforeSend: function(){
    		          	html += '<div class="weui-loadmore">';
    					html += '<i class="weui-loading"></i>';
    					html += '<span class="weui-loadmore__tips">正在加载</span>';
    					html += '</div>';
    					$("#weui-row").append(html);
    				  },
    		  complete:function(XMLHttpRequest,textStatus){
                     $(".weui-loadmore").remove();
          		 },
               error: function (request) {
                   $(".loading").hide();
                   $.hideLoading();         
                   html += '<div class="no-product"><div class="iconfont icon-shangcheng"></div><div>没有找到相关店铺~</div></div>';
                   $("#weui-row").append(html);
               },
               success: function (data) {
                   count = data._meta.pageCount;
                   if(data._meta.pageCount==0){
                        $.hideLoading();
                        $(".loading").hide();
                        html += '<div class="no-product"><div class="iconfont icon-shangcheng"></div><div>没有找到相关店铺~</div></div>';
                        $("#weui-row").append(html);
                        return;
                   }else if(data._meta.pageCount==1&&pages!=1){
                   		return;
                   }else
                   {
                       for (var i = 0; i < data.items.length; i++) {
                          html += '<li class="list-col1-item" onclick="window.location.href=\'/shop/index?shop_id='+data.items[i].id+'\'">';
                          html += '<div class="item-pic"><img src="'+data.items[i].logo+'" alt=""></div>';
                          html += '<div class="item-r">';
                          html += '<p class="name">'+data.items[i].name+'</p>';
                          html += '<div class="shop">';
                          html += '<p class="shop-addr">'+data.items[i].address ? data.items[i].address : ''+'</p>';
                          html += '<p class="shop-tel">'+data.items[i].mobile ? data.items[i].mobile : ''+'</p>';
                          html += '</div>';
                          html += '<div class="go-shop">进店</div>';
                          html += '</div>';
                          html += '</li>';
                       }
                       $("#weui-row").append(html);
                       $.hideLoading();
                       $(".loading").hide();
                    }
               }
           });

   }
    //搜索功能
    $(".ico-search").click(function(){
            name=$('.textarea').val();
            $("#weui-row").html('');
            pages=1;
            loadlist();
    })
    $('.search').keyup(function(evt){
      if (evt.keyCode == 13) {
          name=$('.search').val();
          $("#weui-row").html('');
          pages=1;
          loadlist();
      }
    });


<?php $this->endBlock(); ?>
<?php $this->registerJs($this->blocks['shop_list'], \yii\web\View::POS_END); ?>  