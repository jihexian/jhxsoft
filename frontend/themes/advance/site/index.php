<?php
use common\helpers\Util;
use common\models\Village;
use yii\helpers\Url;
use common\models\Nav as NavModel;

?>

<!-- 轮播图 -->
        <div class="swiper-container index-ban">
            <div class="swiper-wrapper">
            	<?php foreach($carousels as $v): ?>
                <div class="swiper-slide">
                	<a href="<?= $v['url']?>">
                    <img src="<?= $v['image']?>" alt="" class="block">
                    </a>
                </div>
                <?php endforeach; ?>

            </div>
            <div class="swiper-pagination"></div>
        </div>
        
        <!-- 搜素 -->
        <div class="top-search">
            <div class="search-btn-wrap">
                <i class="ico-search"></i>
                <input type="search" class="textarea search" placeholder="搜索您需要的产品">
            </div>
        </div>
        <!-- 搜素 -->
        <!-- 分类 -->
        <div class="idx-grids fenlei">
            <div class="weui-grids">
            
            	<?php foreach($types as $v): ?>

               <a href="<?=\yii\helpers\Url::to($v['url']) ?>" class="weui-grid">
                    <div class="weui-grid__icon">
                         <img src="<?=$v['icon']?>" alt="">
                    </div>
                   <p class="weui-grid__label"><?=$v['label']?></p>   
                </a>
                 <?php endforeach; ?>

            </div>
        </div>
        <!-- 分类 -->
        <!-- 广告区 -->
        <div class="guanggaoqu">
        <?php foreach($ads as $v): ?>
        	<a href="<?= $v['url']?>">
            <img src="<?= $v['image']?>">
            </a>
        <?php endforeach; ?>
        
        </div>
        <!-- 广告区结束 -->

        <!-- 扶贫商家 -->
        <a href="<?=Url::to(['shop/lists'])?>" class="index-pro-category">
            <div class="tit">扶贫商品</div>
            <div class="more iconfont icon-dayuhao"></div>
        </a>
        <div id="list" class="list">
            <div class="list-col-1">
                <ul class="weui-row">
                <?php foreach ($product as $v):?>

                    <li>
                       
                             <a href="<?=Url::to(['product/detail','id'=>$v['product_id']])?>"  class="list-col1-item"><div class="item-pic"><img src="<?=count($v['image'])>0?$v['image'][0]['thumbImg']:Yii::$app->params['defaultImg']['default']?>" alt=""></div></a>
                            <div class="item-txt">
                               <a href="<?=Url::to(['product/detail','id'=>$v['product_id']])?>"><p class="name"><?=$v['name']?></p></a>
                                <div class="shop">


                                   <p class="shop-name"><?php echo $v['shop']['name'];?></p>
                                   <p class="goshop"><a href="<?=Url::to(['shop/index','shop_id'=>$v['shop']['id']])?>">进店</a></p> 


                                </div>
                                <p class="price">￥<?=$v['min_price']?></p>
                                <div class="item-txt-btm">
                                  <div>
                                    <?php if($v['shop']['is_village'] && $v['shop']['percent']): ?>
                                        <p>捐赠<?=$v['shop']['percent']*100?>%的扶贫基金</p>
                                    <?php endif;?>

                                  </div>  
                                   <a href="<?=Url::to(['product/detail','id'=>$v['product_id']])?>"  class="list-col1-item">
                                  <span class="buy">立即购买</span>
                                  </a>
                                </div>
                            </div>
                      
                    </li>
                    <?php endforeach;?>
               
                </ul>
            </div>
        </div>


        <!-- 商家end -->
        <div class="list-tit">
            <div>新品上市</div>
        </div>
        <div id="list" class="list">
            <div class="list-col-2">
                <ul class="weui-row ">
                
                	<?php foreach($news as $v): ?>
                    <li class="weui-col-50">
                        <a href="<?= \yii\helpers\Url::to(['product/detail','id'=>$v['product_id']])?>">
                            <div class="item-pic"><img src="<?= count($v['image'])>0? $v['image'][0]['url'].'?imageView2/1/w/300/h/300':Yii::$app->params['defaultImg']['default']?>" alt=""></div>
                            <div class="item-txt">
                                <p><?= $v['name']?></p>
                                <div class="price-line">
                                    <span>¥<?= $v['min_price']?></span>
                                    <!--<del>¥40</del>-->                                    
                                </div>
                                <div class="donation">
                                    <?php if($v['shop']['is_village'] && $v['shop']['percent']): ?>
                                        <p>捐赠<?=$v['shop']['percent']*100?>%的扶贫基金</p>
                                    <?php endif;?>
                                </div>  
                            </div>
                        </a>
                    </li>
                    <?php endforeach; ?>

                </ul>
            </div>
            <a href="<?= \yii\helpers\Url::to(['product/index'])?>" class="more">查看更多</a>
        </div>
        <div class="list-tit">
            <div>精品推荐</div>
        </div>
        <div id="list" class="list">
            <div class="list-col-2">
                <ul class="weui-row ">
        
                    <?php foreach($hots as $v): ?>
                    <li class="weui-col-50">
                        <a href="<?= \yii\helpers\Url::to(['product/detail','id'=>$v['product_id']])?>">
                            <div class="item-pic"><img src="<?= count($v['image'])>0? $v['image'][0]['url'].'?imageView2/1/w/300/h/300':Yii::$app->params['defaultImg']['default']?>" alt=""></div>
                            <div class="item-txt">
                                <p><?= $v['name']?></p>
                                <div class="price-line">
                                    <span>¥<?= $v['min_price']?></span>
                                    <!--<del>¥40</del>-->                                    
                                </div>
                                  <div class="donation">
                                    <?php if($v['shop']['is_village'] && $v['shop']['percent']): ?>
                                        <p>捐赠<?=$v['shop']['percent']*100?>%的扶贫基金</p>
                                    <?php endif;?>
                                </div>  
                            </div>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="<?= \yii\helpers\Url::to(['product/index','is_top'=>1])?>" class="more">查看更多</a>
        </div>
 <script>
var get_index="<?=Yii::$app->request->get('index','')?>";
var title="<?=Yii::$app->config->get('site_name')?>";
var desc="<?=Yii::$app->config->get('seo_site_description')?>";
var logo="<?=Yii::$app->config->get('site_logo')?>";
var site_url="<?=Yii::$app->config->get('SITE_URL')?>";
</script>     
<?php
$this->registerJs(<<<JS
    var name = '';
    var swiper = new Swiper('.index-ban', {
        pagination: '.index-ban .swiper-pagination',
        autoplay: 3000,
        loop: true
    });
    //搜索功能
    $(".ico-search").click(function(){
            name=$('.textarea').val();
            window.location.href="/product/index?name="+name; 
    })
    $('.search').keyup(function(evt){
        if (evt.keyCode == 13) {
             name=$('.search').val();
            window.location.href="/product/index?name="+name;
        }   
    });
<!--分享代码开始-->

     //微信相关代码
    wx.error(function (res) {
        //alert(res.errMsg);
    });
    wx.ready(function () {
        // 自定义“分享给朋友”及“分享到QQ”按钮的分享内容
      
       wx.onMenuShareAppMessage({
            title:title, // 分享标题
            desc: desc, // 分享描述
            link:site_url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: logo, // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
            // 用户点击了分享后执行的回调函数
            }
            });
       //自定义“分享到朋友圈”及“分享到QQ空间”按钮的分享内容
       wx.onMenuShareTimeline({
            title:title, // 分享标题
            desc: desc, // 分享描述
            link:site_url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: logo, // 分享图标
            success: function () {
                 // alert("分享成功！");
            }
        });
    });
    

    $.ajax({
        type: "GET",
        data: "",
        url: "/wx/sign",
        dataType: 'json',
        success: function (data) {
            wx.config({
                debug: false,
                appId: data.appId,
                timestamp: data.timestamp,
                nonceStr: data.nonceStr,
                signature: data.signature,
                jsApiList: ['onMenuShareAppMessage', 'onMenuShareTimeline']
            });
        },
        error: function () {
            alert("接口请求失败！");
        }
    });

<!--分享代码结束-->   
JS
);
?>